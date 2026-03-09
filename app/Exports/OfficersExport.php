<?php

namespace App\Exports;

use App\Models\SppgOfficer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class OfficersExport extends DefaultValueBinder implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithEvents,
    WithCustomValueBinder
{
    use Exportable;

    protected $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function query()
    {
        return SppgOfficer::query()->with([
            'person.user.role',
            'person.position',
            'sppgUnit',
            'position'
        ]);
    }

    public function bindValue(Cell $cell, $value)
    {
        // Menangani angka panjang agar tidak menjadi format scientific (E+)
        if (is_numeric($value) && strlen($value) > 10) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function headings(): array
    {
        $mappingNames = [
            'status_officer' => 'STATUS PENGGUNA',
            'role' => 'HAK AKSES SISTEM',
            'nik' => 'NIK',
            'no_kk' => 'NO. KK (Wajib)',
            'name' => 'NAMA LENGKAP',
            'gender' => 'JENIS KELAMIN',
            'place_birthday' => 'TEMPAT LAHIR',
            'date_birthday' => 'TANGGAL LAHIR',
            'age' => 'UMUR',
            'religion' => 'AGAMA',
            'marital_status' => 'STATUS PERNIKAHAN',
            'no_bpjs_kes' => 'NO. BPJS KESEHATAN',
            'no_bpjs_tk' => 'NO. BPJS KETENAGAKERJAAN',
            'phone' => 'TELEPON',
            'position' => 'JABATAN',
            'unit_name' => 'UNIT SPPG',
            'daily_honor' => 'HONOR PER HARI',
            'province_ktp' => 'PROVINSI (KTP)',
            'regency_ktp' => 'KABUPATEN (KTP)',
            'district_ktp' => 'KECAMATAN (KTP)',
            'village_ktp' => 'DESA/KELURAHAN (KTP)',
            'address_ktp' => 'ALAMAT JALAN/RUMAH (KTP)',
            'province_domicile' => 'PROVINSI (DOMISILI)',
            'regency_domicile' => 'KABUPATEN (DOMISILI)',
            'district_domicile' => 'KECAMATAN (DOMISILI)',
            'village_domicile' => 'DESA/KELURAHAN (DOMISILI)',
            'address_domicile' => 'ALAMAT JALAN/RUMAH (DOMISILI)',
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($officer): array
    {
        $p = $officer->person;
        $u = $p?->user;
        
        return collect($this->columns)->map(function ($col) use ($officer, $p, $u) {
            return match ($col) {
                'status_officer' => $officer->is_active ? 'Aktif' : 'Non-Aktif',
                'role' => $u?->role->name_role ?? '-',
                'name' => $p?->name ?? '-',
                'nik' => $p?->nik ?? '-',
                'no_kk' => $p?->no_kk ?? '-',
                'gender' => ($p?->gender == 'L' ? 'Laki-laki' : ($p?->gender == 'P' ? 'Perempuan' : '-')),
                'place_birthday' => $p?->place_birthday ?? '-',
                'date_birthday' => $p?->date_birthday ?? '-',
                'age' => $p?->age ?? '-',
                'religion' => $p?->religion ?? '-',
                'marital_status' => $p?->marital_status ?? '-',
                'no_bpjs_kes' => $p?->no_bpjs_kes ?? '-',
                'no_bpjs_tk' => $p?->no_bpjs_tk ?? '-',
                'phone' => $u?->phone ?? '-',
                'position' => $officer->position?->name_position ?? '-',
                'unit_name' => $officer->sppgUnit?->name ?? '-',
                'daily_honor' => $officer->daily_honor,
                'province_ktp' => $p?->province_ktp ?? '-',
                'regency_ktp' => $p?->regency_ktp ?? '-',
                'district_ktp' => $p?->district_ktp ?? '-',
                'village_ktp' => $p?->village_ktp ?? '-',
                'address_ktp' => $p?->address_ktp ?? '-',
                'province_domicile' => $p?->province_domicile ?? '-',
                'regency_domicile' => $p?->regency_domicile ?? '-',
                'district_domicile' => $p?->district_domicile ?? '-',
                'village_domicile' => $p?->village_domicile ?? '-',
                'address_domicile' => $p?->address_domicile ?? '-',
                default => '-',
            };
        })->toArray();
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
