<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OfficerTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, \Maatwebsite\Excel\Concerns\WithEvents
{
    protected $positions;

    public function __construct($positions = [])
    {
        $this->positions = $positions;
    }

    public function array(): array
    {
        // Baris contoh data (Dummy) yang sesuai dengan kategori valid
        // Menggunakan NIK & Telepon yang belum ada di seeder/database
        // Menggunakan jabatan relawan: Cuci Ompreng, Asisten Lapangan, Kebersihan
        return [
            [
                'Administrator',
                '5102012345670001',
                '5102012345670002',
                'Agus Setiawan',
                'L',
                'Buleleng',
                '10-05-1992',
                'Islam',
                'Kawin',
                '081299991111',
                'Asisten Lapangan',
                'SPPG Buleleng Banjar Dencarik',
                '120000',
                'Aktif',
                '0001112223334',
                '9991112223334',
                'Bali', 'Buleleng', 'Sukasada', 'Ambengan', 'Jl. Langit Biru No. 1',
                'Bali', 'Buleleng', 'Sukasada', 'Ambengan', 'Jl. Langit Biru No. 1'
            ],
            [
                'Editor',
                '5102012345670003',
                '5102012345670004',
                'Siti Aminah',
                'P',
                'Singaraja',
                '15-08-1998',
                'Islam',
                'Belum Kawin',
                '081299992222',
                'Cuci Ompreng',
                'SPPG Buleleng Sukasada Pancasari',
                '100000',
                'Aktif',
                null,
                null,
                'Bali', 'Buleleng', 'Buleleng', 'Kampung Anyar', 'Jl. Melati No. 5',
                'Bali', 'Buleleng', 'Buleleng', 'Kampung Anyar', 'Jl. Melati No. 5'
            ],
            [
                'Author',
                '5102012345670005',
                '5102012345670006',
                'Ketut Wirawan',
                'L',
                'Seririt',
                '25-12-1985',
                'Hindu',
                'Kawin',
                '081299993333',
                'Kebersihan',
                'SPPG Buleleng Banjar Dencarik',
                '90000',
                'Aktif',
                null,
                null,
                'Bali', 'Buleleng', 'Seririt', 'Banjar', 'Jl. Pahlawan No. 10',
                'Bali', 'Buleleng', 'Seririt', 'Banjar', 'Jl. Pahlawan No. 10'
            ]
        ];
    }

    public function headings(): array
    {
        $posExamples = !empty($this->positions) ? implode('/', $this->positions) : 'Ketua/Sekretaris';

        return [
            'HAK AKSES SISTEM (Administrator/Editor/Author/Subscriber/Guest)',
            'NIK',
            'NO. KK',
            'NAMA LENGKAP',
            'JENIS KELAMIN (L/P)',
            'TEMPAT LAHIR',
            'TGL LAHIR (DD-MM-YYYY)',
            'AGAMA (Islam/Kristen/Katolik/Hindu/Budha/Konghucu)',
            'STATUS PERNIKAHAN (Belum Kawin/Kawin/Janda/Duda)',
            'TELEPON',
            "JABATAN ($posExamples)",
            'UNIT SPPG',
            'HONOR PER HARI',
            'STATUS KEAKTIFAN (Aktif/Non-Aktif)',
            'NO. BPJS KESEHATAN',
            'NO. BPJS KETENAGAKERJAAN',
            'PROVINSI (KTP)',
            'KABUPATEN (KTP)',
            'KECAMATAN (KTP)',
            'DESA/KELURAHAN (KTP)',
            'ALAMAT JALAN (KTP)',
            'PROVINSI (DOMISILI)',
            'KABUPATEN (DOMISILI)',
            'KECAMATAN (DOMISILI)',
            'DESA/KELURAHAN (DOMISILI)',
            'ALAMAT JALAN (DOMISILI)'
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
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $event->sheet->getAutoFilter()->setRange('A1:' . $lastColumn . '1');
            },
        ];
    }
}
