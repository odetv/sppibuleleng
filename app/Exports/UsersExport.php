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
            'person.workAssignment.sppgUnit.workAssignments.decree',
            'person.workAssignment.sppgUnit.leader',
            'person.workAssignment.sppgUnit.nutritionist',
            'person.workAssignment.sppgUnit.accountant',
            'person.workAssignment.sppgUnit.socialMedia',
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
            'nik' => 'NIK',
            'no_kk' => 'NO. KK',
            'name' => 'NAMA LENGKAP',
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
            'email' => 'EMAIL',
            'phone' => 'TELEPON',
            'employment_status' => 'STATUS KERJA',
            'batch' => 'BATCH',
            'position' => 'JABATAN',
            'payroll_bank_name' => 'NAMA BANK PAYROLL',
            'payroll_bank_account_number' => 'NOMOR REKENING PAYROLL',
            'payroll_bank_account_name' => 'NAMA PEMILIK REKENING PAYROLL',
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
            'sppg_id' => 'ID SPPG',
            'sppg_code' => 'KODE SPPG',
            'sppg_name' => 'NAMA SPPG',
            'sppg_status' => 'STATUS SPPG',
            'sppg_operational_date' => 'TANGGAL OPERASIONAL SPPG',
            'sppg_province' => 'PROVINSI SPPG',
            'sppg_regency' => 'KABUPATEN SPPG',
            'sppg_district' => 'KECAMATAN SPPG',
            'sppg_village' => 'DESA/KELURAHAN SPPG',
            'sppg_address' => 'ALAMAT JALAN/RUMAH SPPG',
            'sppg_latitude' => 'LATITUDE GPS SPPG',
            'sppg_longitude' => 'LONGITUDE GPS SPPG',
            'sppg_leader' => 'NAMA KEPALA SPPG (KASPPG)',
            'sppg_nutritionist' => 'NAMA AHLI GIZI SPPG (AG)',
            'sppg_accountant' => 'NAMA AKUNTAN SPPG (AK)',
            'sppg_photo' => 'LINK FILE FOTO SPPG',
            'sppg_facebook_url' => 'LINK AKUN FACEBOOK (SPPG)',
            'sppg_instagram_url' => 'LINK AKUN INSTAGRAM (SPPG)',
            'sppg_tiktok_url' => 'LINK AKUN TIKTOK (SPPG)',
            'sk_kasppg_number' => 'NOMOR SK KASPPG',
            'sk_kasppg_date' => 'TANGGAL SK KASPPG',
            'sk_kasppg_ba_verval_number' => 'NOMOR BA VERVAL KASPPG',
            'sk_kasppg_ba_verval_date' => 'TANGGAL BA VERVAL KASPPG',
            'sk_kasppg_file' => 'LINK FILE SK KASPPG',
            'sk_ag_number' => 'NOMOR SK AG',
            'sk_ag_date' => 'TANGGAL SK AG',
            'sk_ag_ba_verval_number' => 'NOMOR BA VERVAL AG',
            'sk_ag_ba_verval_date' => 'TANGGAL BA VERVAL AG',
            'sk_ag_file' => 'LINK FILE SK AG',
            'sk_ak_number' => 'NOMOR SK AK',
            'sk_ak_date' => 'TANGGAL SK AK',
            'sk_ak_ba_verval_number' => 'NOMOR BA VERVAL AK',
            'sk_ak_ba_verval_date' => 'TANGGAL BA VERVAL AK',
            'sk_ak_file' => 'LINK FILE SK AK',
            'photo' => 'LINK FILE FOTO PROFIL',
            'facebook_url' => 'LINK AKUN FACEBOOK (PRIBADI)',
            'instagram_url' => 'LINK AKUN INSTAGRAM (PRIBADI)',
            'tiktok_url' => 'LINK AKUN TIKTOK (PRIBADI)',
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($user): array
    {
        $p = $user->person;
        $sm = $p?->socialMedia;
        
        $skKasppg = null;
        $skAg = null;
        $skAk = null;
        $skKasppgSppgId = null;
        $skAgSppgId = null;
        $skAkSppgId = null;

        if ($p?->workAssignment?->sppgUnit) {
            $unitWA = $p->workAssignment->sppgUnit->workAssignments;
            foreach ($unitWA as $wa) {
                if ($wa->decree) {
                    // ID 4 = KASPPG, ID 5 = AG, ID 6 = AK
                    if ($wa->decree->type_sk == 4) {
                        $skKasppg = $wa->decree;
                        $skKasppgSppgId = $wa->id_sppg_unit;
                    }
                    elseif ($wa->decree->type_sk == 5) {
                        $skAg = $wa->decree;
                        $skAgSppgId = $wa->id_sppg_unit;
                    }
                    elseif ($wa->decree->type_sk == 6) {
                        $skAk = $wa->decree;
                        $skAkSppgId = $wa->id_sppg_unit;
                    }
                }
            }
        }

        return collect($this->columns)->map(function ($col) use ($user, $p, $sm, $skKasppg, $skAg, $skAk, $skKasppgSppgId, $skAgSppgId, $skAkSppgId) {
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
                'sppg_id' => $p?->workAssignment?->sppgUnit?->id_sppg_unit ?? '-',
                'sppg_code' => $p?->workAssignment?->sppgUnit?->code_sppg_unit ?? '-',
                'sppg_name' => $p?->workAssignment?->sppgUnit?->name ?? '-',
                'sppg_status' => $p?->workAssignment?->sppgUnit?->status ?? '-',
                'sppg_operational_date' => $p?->workAssignment?->sppgUnit?->operational_date ?? '-',
                'sppg_photo' => $p?->workAssignment?->sppgUnit?->photo ? asset('storage/' . $p->workAssignment->sppgUnit->photo) : '-',
                'sppg_province' => $p?->workAssignment?->sppgUnit?->province ?? '-',
                'sppg_regency' => $p?->workAssignment?->sppgUnit?->regency ?? '-',
                'sppg_district' => $p?->workAssignment?->sppgUnit?->district ?? '-',
                'sppg_village' => $p?->workAssignment?->sppgUnit?->village ?? '-',
                'sppg_address' => $p?->workAssignment?->sppgUnit?->address ?? '-',
                'sppg_latitude' => $p?->workAssignment?->sppgUnit?->latitude_gps ?? '-',
                'sppg_longitude' => $p?->workAssignment?->sppgUnit?->longitude_gps ?? '-',
                'sppg_leader' => $p?->workAssignment?->sppgUnit?->leader?->name ?? '-',
                'sppg_nutritionist' => $p?->workAssignment?->sppgUnit?->nutritionist?->name ?? '-',
                'sppg_accountant' => $p?->workAssignment?->sppgUnit?->accountant?->name ?? '-',
                'sppg_facebook_url' => $p?->workAssignment?->sppgUnit?->socialMedia?->facebook_url ?? '-',
                'sppg_instagram_url' => $p?->workAssignment?->sppgUnit?->socialMedia?->instagram_url ?? '-',
                'sppg_tiktok_url' => $p?->workAssignment?->sppgUnit?->socialMedia?->tiktok_url ?? '-',
                'sk_kasppg_number' => $skKasppg?->no_sk ?? '-',
                'sk_kasppg_date' => $skKasppg?->date_sk ?? '-',
                'sk_kasppg_ba_verval_number' => $skKasppg?->no_ba_verval ?? '-',
                'sk_kasppg_ba_verval_date' => $skKasppg?->date_ba_verval ?? '-',
                'sk_kasppg_file' => $skKasppg?->file_sk ? asset("storage/sppgunits/" . md5($skKasppgSppgId . config('app.key')) . "/files/" . md5($skKasppg->id_assignment_decree . config('app.key')) . "/{$skKasppg->file_sk}") : '-',
                'sk_ag_number' => $skAg?->no_sk ?? '-',
                'sk_ag_date' => $skAg?->date_sk ?? '-',
                'sk_ag_ba_verval_number' => $skAg?->no_ba_verval ?? '-',
                'sk_ag_ba_verval_date' => $skAg?->date_ba_verval ?? '-',
                'sk_ag_file' => $skAg?->file_sk ? asset("storage/sppgunits/" . md5($skAgSppgId . config('app.key')) . "/files/" . md5($skAg->id_assignment_decree . config('app.key')) . "/{$skAg->file_sk}") : '-',
                'sk_ak_number' => $skAk?->no_sk ?? '-',
                'sk_ak_date' => $skAk?->date_sk ?? '-',
                'sk_ak_ba_verval_number' => $skAk?->no_ba_verval ?? '-',
                'sk_ak_ba_verval_date' => $skAk?->date_ba_verval ?? '-',
                'sk_ak_file' => $skAk?->file_sk ? asset("storage/sppgunits/" . md5($skAkSppgId . config('app.key')) . "/files/" . md5($skAk->id_assignment_decree . config('app.key')) . "/{$skAk->file_sk}") : '-',
                'payroll_bank_name' => $p?->payroll_bank_name ?? '-',
                'payroll_bank_account_number' => $p?->payroll_bank_account_number ?? '-',
                'payroll_bank_account_name' => $p?->payroll_bank_account_name ?? '-',
                'photo' => $p?->photo ? asset('storage/' . $p->photo) : '-',
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
