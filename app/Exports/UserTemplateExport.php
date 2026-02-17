<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'EMAIL PENGGUNA',
            'NOMOR WHATSAPP',
            'HAK AKSES (Administrator/Author/Editor/Subscriber/Guest)',
            'PASSWORD'
        ];
    }

    public function array(): array
    {
        return [
            ['contoh@email.com', '081234567891', 'Guest', 'Password@123']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
