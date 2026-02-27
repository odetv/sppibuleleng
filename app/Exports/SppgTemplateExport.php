<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SppgTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'ID SPPG',
            'KODE SPPG',
            'NAMA SPPG',
            'STATUS (Operasional/Belum Operasional/Tutup Sementara/Tutup Permanen)',
            'TANGGAL OPERASIONAL (YYYY-MM-DD)',
            'PROVINSI',
            'KABUPATEN',
            'KECAMATAN',
            'DESA/KELURAHAN',
            'ALAMAT LENGKAP',
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
                '2024-01-01',
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
}
