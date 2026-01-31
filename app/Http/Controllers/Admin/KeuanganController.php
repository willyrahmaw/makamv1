<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Exports\KeuanganExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class KeuanganController extends Controller
{
    private static function normalizeDateInput(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $value = trim($value);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
            $d = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $mo = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            return $m[3] . '-' . $mo . '-' . $d;
        }
        return $value;
    }

    public function index(Request $request)
    {
        $tanggalMulai = self::normalizeDateInput($request->input('tanggal_mulai'));
        $tanggalSelesai = self::normalizeDateInput($request->input('tanggal_selesai'));
        $query = Keuangan::query();

        // Filter tanggal
        if ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        // Filter tipe
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $query->where('tipe', $request->tipe);
        }

        // Pencarian teks
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('sumber', 'like', "%{$q}%")
                    ->orWhere('donatur', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('referensi', 'like', "%{$q}%");
            });
        }

        $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc');

        $keuangan = $query->paginate(20)->withQueryString();

        // Buat query terpisah untuk ringkasan dan chart (tanpa orderBy untuk aggregasi)
        $baseQuery = Keuangan::query();
        
        // Terapkan filter yang sama
        if ($tanggalMulai) {
            $baseQuery->whereDate('tanggal', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $baseQuery->whereDate('tanggal', '<=', $tanggalSelesai);
        }
        if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
            $baseQuery->where('tipe', $request->tipe);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $baseQuery->where(function ($sub) use ($q) {
                $sub->where('sumber', 'like', "%{$q}%")
                    ->orWhere('donatur', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('referensi', 'like', "%{$q}%");
            });
        }

        // Hitung ringkasan profesional
        $totalPemasukan = (clone $baseQuery)->where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $baseQuery)->where('tipe', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Data untuk chart - 12 bulan terakhir
        $chartData = $this->getChartData($baseQuery);

        return view('admin.keuangan.index', compact(
            'keuangan',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'chartData'
        ));
    }

    public function create()
    {
        return view('admin.keuangan.create');
    }

    public function store(Request $request)
    {
        $request->merge(['tanggal' => self::normalizeDateInput($request->input('tanggal'))]);
        $data = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'sumber' => 'nullable|string|max:255',
            'metode' => 'nullable|string|max:100',
            'referensi' => 'nullable|string|max:100',
            'donatur' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        Keuangan::create($data);

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil ditambahkan.');
    }

    public function edit(Keuangan $keuangan)
    {
        return view('admin.keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $request->merge(['tanggal' => self::normalizeDateInput($request->input('tanggal'))]);
        $data = $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'sumber' => 'nullable|string|max:255',
            'metode' => 'nullable|string|max:100',
            'referensi' => 'nullable|string|max:100',
            'donatur' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        $keuangan->update($data);

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan)
    {
        $keuangan->delete();

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil dihapus.');
    }

    public function export(Request $request)
    {
        try {
            $tanggalMulai = self::normalizeDateInput($request->input('tanggal_mulai'));
            $tanggalSelesai = self::normalizeDateInput($request->input('tanggal_selesai'));
            $query = Keuangan::query();
            $filters = [];

            // Filter tanggal
            if ($tanggalMulai) {
                $query->whereDate('tanggal', '>=', $tanggalMulai);
                $filters['Tanggal Mulai'] = $tanggalMulai;
            }
            if ($tanggalSelesai) {
                $query->whereDate('tanggal', '<=', $tanggalSelesai);
                $filters['Tanggal Selesai'] = $tanggalSelesai;
            }

            // Filter tipe
            if ($request->filled('tipe') && in_array($request->tipe, ['pemasukan', 'pengeluaran'])) {
                $query->where('tipe', $request->tipe);
                $filters['Tipe'] = ucfirst($request->tipe);
            }

            // Pencarian teks
            if ($request->filled('q')) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('sumber', 'like', "%{$q}%")
                        ->orWhere('donatur', 'like', "%{$q}%")
                        ->orWhere('deskripsi', 'like', "%{$q}%")
                        ->orWhere('referensi', 'like', "%{$q}%");
                });
                $filters['Pencarian'] = $q;
            }

            $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc');

            // Hitung total
            $baseQuery = clone $query;
            $totalPemasukan = (clone $baseQuery)->where('tipe', 'pemasukan')->sum('nominal');
            $totalPengeluaran = (clone $baseQuery)->where('tipe', 'pengeluaran')->sum('nominal');
            $saldo = $totalPemasukan - $totalPengeluaran;

            $format = $request->get('format', 'excel'); // excel atau csv

            if ($format === 'excel') {
                return $this->exportExcel($query, $totalPemasukan, $totalPengeluaran, $saldo, $filters);
            }

            // CSV fallback
            return $this->exportCsv($query, $totalPemasukan, $totalPengeluaran, $saldo, $filters);
        } catch (\Exception $e) {
            return redirect()->route('admin.keuangan.index')
                ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    private function exportCsv($query, $totalPemasukan, $totalPengeluaran, $saldo, $filters = [])
    {
        $export = new KeuanganExport($query, $totalPemasukan, $totalPengeluaran, $saldo, $filters);
        $data = $export->getData();
        $headings = $export->getHeadings();
        $summary = $export->getSummary();
        $filterInfo = $export->getFilters();

        $filename = 'laporan-keuangan-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ];

        $callback = function() use ($headings, $data, $export, $summary, $filterInfo) {
            // BOM untuk UTF-8
            echo "\xEF\xBB\xBF";
            
            $file = fopen('php://output', 'w');
            
            // Header Laporan
            fputcsv($file, ['LAPORAN KEUANGAN'], ';');
            fputcsv($file, ['Tanggal Export: ' . date('d/m/Y H:i:s')], ';');
            
            // Filter yang diterapkan
            if (!empty($filterInfo)) {
                fputcsv($file, [], ';');
                fputcsv($file, ['Filter yang Diterapkan:'], ';');
                foreach ($filterInfo as $key => $value) {
                    fputcsv($file, [$key . ': ' . $value], ';');
                }
            }
            
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

    private function exportExcel($query, $totalPemasukan, $totalPengeluaran, $saldo, $filters = [])
    {
        $export = new KeuanganExport($query, $totalPemasukan, $totalPengeluaran, $saldo, $filters);
        $filename = 'laporan-keuangan-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download($export, $filename);
    }

    private function getChartData($query)
    {
        // Data untuk line chart - 12 bulan terakhir
        $months = [];
        $pemasukanData = [];
        $pengeluaranData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');
            
            $months[] = $monthLabel;
            
            $pemasukan = (clone $query)
                ->where('tipe', 'pemasukan')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('nominal');
            
            $pengeluaran = (clone $query)
                ->where('tipe', 'pengeluaran')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('nominal');
            
            $pemasukanData[] = (float) $pemasukan;
            $pengeluaranData[] = (float) $pengeluaran;
        }

        // Data untuk pie chart - breakdown sumber pemasukan
        $pemasukanBySource = (clone $query)
            ->where('tipe', 'pemasukan')
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
        $pengeluaranBySource = (clone $query)
            ->where('tipe', 'pengeluaran')
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
}

