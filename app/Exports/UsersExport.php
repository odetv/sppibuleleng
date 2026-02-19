<?php

namespace App\Exports;

use App\Models\User;
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

class UsersExport extends DefaultValueBinder implements
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
        return User::query()->with([
            'role',
            'person.position',
            'person.workAssignment.sppgUnit',
            'person.socialMedia'
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
            'status_user' => 'STATUS AKUN',
            'role' => 'HAK AKSES SISTEM',
            'name' => 'NAMA LENGKAP',
            'nik' => 'NIK',
            'no_kk' => 'NO. KK',
            'nip' => 'NIP',
            'npwp' => 'NPWP',
            'no_bpjs_kes' => 'NO. BPJS KESEHATAN',
            'no_bpjs_tk' => 'NO. BPJS KETENAGAKERJAAN',
            'gender' => 'JENIS KELAMIN',
            'place_birthday' => 'TEMPAT LAHIR',
            'date_birthday' => 'TANGGAL LAHIR',
            'age' => 'UMUR',
            'religion' => 'AGAMA',
            'marital_status' => 'STATUS PERNIKAHAN',
            'last_education' => 'PENDIDIKAN TERAKHIR',
            'title_education' => 'GELAR BELAKANG',
            'major_education' => 'JURUSAN/PROGRAM STUDI',
            'clothing_size' => 'UKURAN BAJU',
            'shoe_size' => 'UKURAN SEPATU',
            'phone' => 'TELEPON',
            'email' => 'EMAIL',
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
            'latitude_gps_domicile' => 'LATITUDE GPS (DOMISILI)',
            'longitude_gps_domicile' => 'LONGITUDE GPS (DOMISILI)',
            'employment_status' => 'STATUS KERJA',
            'batch' => 'BATCH',
            'position' => 'JABATAN',
            'work_assignment' => 'UNIT PENUGASAN',
            'payroll_bank_name' => 'NAMA BANK PAYROLL',
            'payroll_bank_account_number' => 'NOMOR REKENING PAYROLL',
            'payroll_bank_account_name' => 'NAMA PEMILIK REKENING PAYROLL',
            'facebook_url' => 'LINK AKUN FACEBOOK',
            'instagram_url' => 'LINK AKUN INSTAGRAM',
            'tiktok_url' => 'LINK AKUN TIKTOK',
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($user): array
    {
        $p = $user->person;
        $sm = $p?->socialMedia;

        return collect($this->columns)->map(function ($col) use ($user, $p, $sm) {
            return match ($col) {
                'status_user' => ucfirst(strtolower($user->status_user)),
                'role' => $user->role->name_role ?? '-',
                'name' => $p?->name ?? '-',
                'nik' => $p?->nik ?? '-',
                'no_kk' => $p?->no_kk ?? '-',
                'nip' => $p?->nip ?? '-',
                'npwp' => $p?->npwp ?? '-',
                'no_bpjs_kes' => $p?->no_bpjs_kes ?? '-',
                'no_bpjs_tk' => $p?->no_bpjs_tk ?? '-',
                'gender' => ($p?->gender == 'L' ? 'Laki-laki' : ($p?->gender == 'P' ? 'Perempuan' : '-')),
                'place_birthday' => $p?->place_birthday ?? '-',
                'date_birthday' => $p?->date_birthday ?? '-',
                'age' => $p?->age ?? '-',
                'religion' => $p?->religion ?? '-',
                'marital_status' => $p?->marital_status ?? '-',
                'last_education' => $p?->last_education ?? '-',
                'title_education' => $p?->title_education ?? '-',
                'major_education' => $p?->major_education ?? '-',
                'clothing_size' => $p?->clothing_size ?? '-',
                'shoe_size' => $p?->shoe_size ?? '-',
                'phone' => $user->phone,
                'email' => $user->email,
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
                'latitude_gps_domicile' => $p?->latitude_gps_domicile ?? '-',
                'longitude_gps_domicile' => $p?->longitude_gps_domicile ?? '-',
                'employment_status' => $p?->employment_status ?? '-',
                'batch' => $p?->batch ?? '-',
                'position' => $p?->position?->name_position ?? '-',
                'work_assignment' => $p?->workAssignment ? ($p->workAssignment->sppgUnit->name . ' - ' . $p->workAssignment->decree->no_sk) : '-',
                'payroll_bank_name' => $p?->payroll_bank_name ?? '-',
                'payroll_bank_account_number' => $p?->payroll_bank_account_number ?? '-',
                'payroll_bank_account_name' => $p?->payroll_bank_account_name ?? '-',
                'facebook_url'  => $sm?->facebook_url ?? '-',
                'instagram_url' => $sm?->instagram_url ?? '-',
                'tiktok_url'    => $sm?->tiktok_url ?? '-',
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
