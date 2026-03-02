<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AssignmentDecreeTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return collect([
            [
                'SK/BGN/2026/001',
                '05-01-2026',
                'BA/V/2026/01',
                '04-01-2026',
                '2DWFSVHQ' // Example valid SPPG Code
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'NOMOR SK',
            'TANGGAL SK (DD-MM-YYYY)',
            'NOMOR BA VERVAL',
            'TANGGAL BA VERVAL (DD-MM-YYYY)',
            'ID SPPG TERKAIT (Pisahkan dengan Koma Jika >1)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $event->sheet->getAutoFilter()->setRange('A1:' . $lastColumn . '1');
            },
        ];
    }
}
