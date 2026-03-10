<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'JENIS SUPPLIER (Koperasi Desa Merah Putih/Koperasi/Bumdes/Bumdesma/UMKM/Supplier Lain)',
            'NAMA SUPPLIER',
            'NAMA PIMPINAN',
            'TELEPON (Hanya Angka)',
            'KOMODITAS (Contoh: Beras, Sayuran, Daging)',
            'PROVINSI',
            'KABUPATEN',
            'KECAMATAN',
            'DESA/KELURAHAN',
            'ALAMAT JALAN',
            'KODE POS',
            'ID UNIT SPPG TERKAIT (Pisahkan dengan koma jika banyak)',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Koperasi Desa Merah Putih',
                'Koperasi Maju Bersama',
                'Bapak Ahmad',
                '08123456789',
                'Beras, Telur, Sayuran',
                'Bali',
                'Buleleng',
                'Buleleng',
                'Baktiseraga',
                'Jl. Ahmad Yani No. 12',
                '81116',
                'SPPG-001, SPPG-002',
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
