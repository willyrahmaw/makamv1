<?php

namespace App\Exports;

use App\Models\Keuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $query;
    protected $totalPemasukan;
    protected $totalPengeluaran;
    protected $saldo;
    protected $filters;

    public function __construct($query, $totalPemasukan = 0, $totalPengeluaran = 0, $saldo = 0, $filters = [])
    {
        $this->query = $query;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->saldo = $saldo;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Tipe',
            'Sumber/Kategori',
            'Donatur',
            'Metode',
            'Referensi',
            'Deskripsi',
            'Nominal (Rp)',
        ];
    }

    public function map($keuangan): array
    {
        static $no = 0;
        $no++;

        // Format nominal dengan tanda + untuk pemasukan dan - untuk pengeluaran
        $nominalFormatted = number_format($keuangan->nominal, 0, ',', '.');
        if ($keuangan->tipe === 'pemasukan') {
            $nominalFormatted = ' Rp ' . $nominalFormatted;
        } else {
            $nominalFormatted = 'Rp ' . $nominalFormatted;
        }

        return [
            $no,
            $keuangan->tanggal->format('d/m/Y'),
            ucfirst($keuangan->tipe),
            $keuangan->sumber ?? '-',
            $keuangan->donatur ?? '-',
            $keuangan->metode ?? '-',
            $keuangan->referensi ?? '-',
            $keuangan->deskripsi ?? '-',
            $nominalFormatted,
        ];
    }

    // Method untuk CSV export
    public function mapRow($keuangan, $no): array
    {
        // Format nominal dengan tanda + untuk pemasukan dan - untuk pengeluaran
        $nominalFormatted = number_format($keuangan->nominal, 0, ',', '.');
        if ($keuangan->tipe === 'pemasukan') {
            $nominalFormatted = '+ Rp ' . $nominalFormatted;
        } else {
            $nominalFormatted = '- Rp ' . $nominalFormatted;
        }

        return [
            $no,
            $keuangan->tanggal->format('d/m/Y'),
            ucfirst($keuangan->tipe),
            $keuangan->sumber ?? '-',
            $keuangan->donatur ?? '-',
            $keuangan->metode ?? '-',
            $keuangan->referensi ?? '-',
            $keuangan->deskripsi ?? '-',
            $nominalFormatted,
        ];
    }

    public function getData()
    {
        return $this->query->get();
    }

    /** Untuk ekspor CSV (controller memanggil getHeadings). */
    public function getHeadings(): array
    {
        return $this->headings();
    }

    public function getSummary(): array
    {
        return [
            ['Total Pemasukan', 'Rp ' . number_format($this->totalPemasukan, 0, ',', '.')],
            ['Total Pengeluaran', 'Rp ' . number_format($this->totalPengeluaran, 0, ',', '.')],
            ['Saldo', 'Rp ' . number_format($this->saldo, 0, ',', '.')],
        ];
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 15,
            'D' => 25,
            'E' => 25,
            'F' => 15,
            'G' => 15,
            'H' => 40,
            'I' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                
                // Hitung jumlah baris untuk insert (header + tanggal + filter + kosong)
                $insertRows = 3; // Header + Tanggal + Baris kosong
                if (!empty($this->filters)) {
                    $insertRows += count($this->filters) + 1; // Label filter + setiap filter + 1
                }
                
                // Insert rows di awal
                $sheet->insertNewRowBefore(1, $insertRows);
                
                // Header Laporan
                $currentRow = 1;
                $sheet->setCellValue('A' . $currentRow, 'LAPORAN KEUANGAN');
                $sheet->mergeCells('A' . $currentRow . ':I' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $currentRow++;
                $sheet->setCellValue('A' . $currentRow, 'Tanggal Export: ' . date('d/m/Y H:i:s'));
                $sheet->mergeCells('A' . $currentRow . ':I' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Filter yang diterapkan
                if (!empty($this->filters)) {
                    $currentRow++;
                    $sheet->setCellValue('A' . $currentRow, 'Filter yang Diterapkan:');
                    $sheet->mergeCells('A' . $currentRow . ':I' . $currentRow);
                    $sheet->getStyle('A' . $currentRow)->applyFromArray([
                        'font' => ['bold' => true],
                    ]);
                    
                    foreach ($this->filters as $key => $value) {
                        $currentRow++;
                        $sheet->setCellValue('A' . $currentRow, $key . ': ' . $value);
                        $sheet->mergeCells('A' . $currentRow . ':I' . $currentRow);
                    }
                }

                // Header tabel (setelah insert rows)
                $headerRow = $insertRows + 1;
                $sheet->getStyle('A' . $headerRow . ':I' . $headerRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Styling data rows
                $dataStartRow = $headerRow + 1;
                $dataEndRow = $lastRow + $insertRows;
                
                if ($dataEndRow >= $dataStartRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':I' . $dataEndRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CCCCCC'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                        ],
                    ]);

                    // Alignment untuk kolom tertentu
                    $sheet->getStyle('A' . $dataStartRow . ':A' . $dataEndRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $sheet->getStyle('B' . $dataStartRow . ':B' . $dataEndRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $sheet->getStyle('I' . $dataStartRow . ':I' . $dataEndRow)->applyFromArray([
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                    ]);
                }

                // Summary
                $summaryRow = $dataEndRow + 2;
                $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN');
                $sheet->mergeCells('A' . $summaryRow . ':B' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                ]);

                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Total Pemasukan');
                $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($this->totalPemasukan, 0, ',', '.'));
                $sheet->getStyle('A' . $summaryRow . ':B' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Total Pengeluaran');
                $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($this->totalPengeluaran, 0, ',', '.'));
                $sheet->getStyle('A' . $summaryRow . ':B' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                $summaryRow++;
                $sheet->setCellValue('A' . $summaryRow, 'Saldo');
                $sheet->setCellValue('B' . $summaryRow, 'Rp ' . number_format($this->saldo, 0, ',', '.'));
                $sheet->getStyle('A' . $summaryRow . ':B' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $this->saldo < 0 ? 'FFE6E6' : 'E6F7E6'],
                    ],
                ]);
            },
        ];
    }
}
