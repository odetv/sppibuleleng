<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BeneficiaryTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'TIPE KELOMPOK (Sekolah/Posyandu)',
            'KATEGORI',
            'KODE PM',
            'NAMA PENERIMA MANFAAT',
            'NAMA PIC',
            'NO TELEPON PIC',
            'EMAIL PIC',
            'PROVINSI',
            'KABUPATEN/KOTA',
            'KECAMATAN',
            'DESA/KELURAHAN',
            'ALAMAT JALAN',
            'LATITUDE GPS',
            'LONGITUDE GPS',
            'PORSI KECIL LAKI-LAKI',
            'PORSI KECIL PEREMPUAN',
            'PORSI BESAR LAKI-LAKI',
            'PORSI BESAR PEREMPUAN',
            'PORSI GURU',
            'PORSI TENAGA KEPENDIDIKAN',
            'PORSI KADER',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Sekolah',
                'SD',
                'KODE-PM-001',
                'SD Negeri 1 Banjar',
                'Bapak Wayan',
                '081234567890',
                'wayan@example.com',
                'Bali',
                'Buleleng',
                'Banjar',
                'Banjar',
                'Jalan Pendidikan No. 1',
                '-8.18844',
                '114.9754',
                '50',
                '60',
                '0',
                '0',
                '10',
                '2',
                '0',
            ],
            [
                'Posyandu',
                'Balita',
                'KODE-PM-002',
                'Posyandu Mawar 1',
                'Ibu Kadek',
                '081987654321',
                'kadek@example.com',
                'Bali',
                'Buleleng',
                'Seririt',
                'Seririt',
                'Jalan Raya Seririt',
                '-8.19231',
                '114.9382',
                '20',
                '25',
                '0',
                '0',
                '0',
                '0',
                '5',
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
