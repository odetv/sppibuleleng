<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SppgTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'ID SPPG',
            'KODE SPPG',
            'NAMA SPPG',
            'STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)',
            'TANGGAL OPERASIONAL (DD-MM-YYYY)',
            'PROVINSI',
            'KABUPATEN',
            'KECAMATAN',
            'DESA/KELURAHAN',
            'ALAMAT JALAN',
            'LATITUDE GPS',
            'LONGITUDE GPS',
        ];
    }

    public function array(): array
    {
        return [
            [
                'SPPG-001',
                'KODE-XYZ',
                'SPPG Buleleng Utara',
                'Operasional',
                '01-01-2024',
                'Bali',
                'Buleleng',
                'Banjar',
                'Banjar',
                'Jalan Raya Banjar No. 123',
                '-8.18844',
                '114.9754'
            ]
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
