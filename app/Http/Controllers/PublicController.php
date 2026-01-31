<?php

namespace App\Http\Controllers;

use App\Models\Makam;
use App\Models\BlokMakam;
use App\Models\Sejarah;
use App\Models\KontakAdmin;
use App\Models\Keuangan;
use App\Exports\KeuanganExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function index()
    {
        $totalMakam = Makam::count();
        $totalBlok = BlokMakam::count();
        $terdata = Makam::where('dikenali', true)->count();
        $tidakDikenali = Makam::where('dikenali', false)->count();

        $makamTerbaru = Makam::with('blok')
                             ->orderBy('id', 'desc')
                             ->take(6)
                             ->get();

        $bloks = BlokMakam::withCount('makam')->get();
        $bloksForDenah = BlokMakam::with('makam')->get(); // Untuk denah dengan data makam lengkap
        $sejarah = Sejarah::getAktif();
        $kontak = KontakAdmin::getKontak();

        return view('public.home', compact('totalMakam', 'totalBlok', 'terdata', 'tidakDikenali', 'makamTerbaru', 'bloks', 'bloksForDenah', 'sejarah', 'kontak'));
    }

    public function search(Request $request)
    {
        $query = Makam::with('blok');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_ayah', 'like', "%{$search}%")
                  ->orWhere('ahli_waris', 'like', "%{$search}%");
            });
        }

        if ($request->filled('blok_id')) {
            $query->where('blok_id', $request->blok_id);
        }

        $makam = $query->orderBy('id', 'desc')->paginate(12);
        $bloks = BlokMakam::all();

        return view('public.search', compact('makam', 'bloks'));
    }

    public function show(Makam $makam)
    {
        $makam->load('blok');
        return view('public.detail', compact('makam'));
    }

    public function denah()
    {
        $bloks = BlokMakam::with('makam')->get();
        return view('public.denah', compact('bloks'));
    }

    public function keuangan()
    {
        // Laporan keuangan publik (hanya baca)
        $keuangan = Keuangan::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $totalPemasukan = Keuangan::where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Keuangan::where('tipe', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Data untuk chart - 12 bulan terakhir
        $chartData = $this->getChartDataKeuangan();

        return view('public.keuangan', compact(
            'keuangan',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'chartData'
        ));
    }

    private function getChartDataKeuangan()
    {
        // Data untuk line chart - 12 bulan terakhir
        $months = [];
        $pemasukanData = [];
        $pengeluaranData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->format('M Y');
            
            $months[] = $monthLabel;
            
            $pemasukan = Keuangan::where('tipe', 'pemasukan')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('nominal');
            
            $pengeluaran = Keuangan::where('tipe', 'pengeluaran')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('nominal');
            
            $pemasukanData[] = (float) $pemasukan;
            $pengeluaranData[] = (float) $pengeluaran;
        }

        // Data untuk pie chart - breakdown sumber pemasukan
        $pemasukanBySource = Keuangan::where('tipe', 'pemasukan')
            ->selectRaw('COALESCE(sumber, "Lainnya") as sumber, SUM(nominal) as total')
            ->groupBy('sumber')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->sumber ?: 'Lainnya',
                    'value' => (float) $item->total
                ];
            });

        // Data untuk pie chart - breakdown kategori pengeluaran
        $pengeluaranBySource = Keuangan::where('tipe', 'pengeluaran')
            ->selectRaw('COALESCE(sumber, "Lainnya") as sumber, SUM(nominal) as total')
            ->groupBy('sumber')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->sumber ?: 'Lainnya',
                    'value' => (float) $item->total
                ];
            });

        return [
            'months' => $months,
            'pemasukan' => $pemasukanData,
            'pengeluaran' => $pengeluaranData,
            'pemasukanBySource' => $pemasukanBySource,
            'pengeluaranBySource' => $pengeluaranBySource,
        ];
    }

    public function exportKeuangan(Request $request)
    {
        try {
            $query = Keuangan::query()->orderBy('tanggal', 'desc')->orderBy('id', 'desc');

            // Hitung total
            $totalPemasukan = Keuangan::where('tipe', 'pemasukan')->sum('nominal');
            $totalPengeluaran = Keuangan::where('tipe', 'pengeluaran')->sum('nominal');
            $saldo = $totalPemasukan - $totalPengeluaran;

            $format = $request->get('format', 'excel');

            if ($format === 'excel') {
                return $this->exportExcelPublic($query, $totalPemasukan, $totalPengeluaran, $saldo);
            }

            // CSV fallback
            return $this->exportCsvPublic($query, $totalPemasukan, $totalPengeluaran, $saldo);
        } catch (\Exception $e) {
            return redirect()->route('keuangan.public')
                ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    private function exportCsvPublic($query, $totalPemasukan, $totalPengeluaran, $saldo)
    {
        $export = new KeuanganExport($query, $totalPemasukan, $totalPengeluaran, $saldo, []);
        $data = $export->getData();
        $headings = $export->getHeadings();
        $summary = $export->getSummary();

        $filename = 'laporan-keuangan-publik-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ];

        $callback = function() use ($headings, $data, $export, $summary) {
            // BOM untuk UTF-8
            echo "\xEF\xBB\xBF";
            
            $file = fopen('php://output', 'w');
            
            // Header Laporan
            fputcsv($file, ['LAPORAN KEUANGAN PUBLIK'], ';');
            fputcsv($file, ['Tanggal Export: ' . date('d/m/Y H:i:s')], ';');
            fputcsv($file, [], ';'); // Baris kosong
            
            // Headings
            fputcsv($file, $headings, ';');
            
            // Data
            $no = 1;
            foreach ($data as $keuangan) {
                fputcsv($file, $export->mapRow($keuangan, $no), ';');
                $no++;
            }
            
            // Baris kosong
            fputcsv($file, [], ';');
            
            // Summary
            fputcsv($file, ['RINGKASAN'], ';');
            foreach ($summary as $row) {
                fputcsv($file, $row, ';');
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportExcelPublic($query, $totalPemasukan, $totalPengeluaran, $saldo)
    {
        $export = new KeuanganExport($query, $totalPemasukan, $totalPengeluaran, $saldo, []);
        $filename = 'laporan-keuangan-publik-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download($export, $filename);
    }

    public function peta()
    {
        $mapEmbedUrl = \App\Models\Settings::get('map_embed_url', '');
        $layananKelurahan = \App\Models\Settings::get('layanan_kelurahan', '');
        return view('public.peta', compact('mapEmbedUrl', 'layananKelurahan'));
    }

    public function showBlok(Request $request, BlokMakam $blok)
    {
        $blok->load('makam');
        
        // Query untuk makam di blok ini dengan search
        $query = $blok->makam();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_ayah', 'like', "%{$search}%")
                  ->orWhere('ahli_waris', 'like', "%{$search}%")
                  ->orWhere('nomor_makam', 'like', "%{$search}%");
            });
        }
        
        $makam = $query->orderBy('id', 'desc')->paginate(12);

        // Ambil semua blok untuk navigasi
        $allBloks = BlokMakam::orderBy('nama_blok')->get();
        
        // Ambil settings untuk warna blok
        $warnaMerah = \App\Models\Settings::get('blok_warna_merah', '#FF6B6B');
        $warnaKuning = \App\Models\Settings::get('blok_warna_kuning', '#FFD93D');
        $warnaHijau = \App\Models\Settings::get('blok_warna_hijau', '#6BCF7F');
        $warnaPutih = \App\Models\Settings::get('blok_warna_putih', '#FFFFFF');
        $thresholdMerah = (int) \App\Models\Settings::get('blok_threshold_merah', '10');
        $thresholdKuning = (int) \App\Models\Settings::get('blok_threshold_kuning', '5');
        
        $jumlahMakam = $blok->makam->count();
        $terdata = $blok->makam()->where('dikenali', true)->count();
        $tidakDikenali = $blok->makam()->where('dikenali', false)->count();
        $warnaBlok = $jumlahMakam == 0 ? $warnaPutih : ($jumlahMakam >= $thresholdMerah ? $warnaMerah : ($jumlahMakam >= $thresholdKuning ? $warnaKuning : $warnaHijau));

        return view('public.blok.show', compact('blok', 'makam', 'allBloks', 'warnaBlok', 'jumlahMakam', 'terdata', 'tidakDikenali', 'thresholdMerah', 'thresholdKuning'));
    }
}
