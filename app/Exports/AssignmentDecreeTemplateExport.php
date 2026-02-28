<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AssignmentDecreeTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return collect([
            [
                'SK/BGN/2026/001',
                '2026-01-05',
                'BA/V/2026/01',
                '2026-01-04',
                '2DWFSVHQ' // Example valid SPPG Code
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'NOMOR SK',
            'TANGGAL SK (YYYY-MM-DD)',
            'NOMOR BA VERVAL',
            'TANGGAL BA VERVAL (YYYY-MM-DD)',
            'ID SPPG TERKAIT (Pisahkan dengan Koma Jika >1)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Header untuk Template Kosong
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Teks Putih
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Background Indigo
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
