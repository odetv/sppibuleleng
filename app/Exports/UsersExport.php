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
            'person.workAssignment.sppgUnit'
        ]);
    }

    /**
     * Menangani format angka panjang (NIK, NIP, Rekening) secara dinamis.
     * Segala sesuatu yang numerik dan panjangnya > 10 digit dipaksa menjadi String.
     */
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && strlen($value) > 10) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function headings(): array
    {
        $mappingNames = [
            'email' => 'EMAIL',
            'phone' => 'WHATSAPP',
            'status_user' => 'STATUS AKUN',
            'role' => 'HAK AKSES',
            'name' => 'NAMA LENGKAP',
            'nik' => 'NIK',
            'no_kk' => 'NO. KK',
            'nip' => 'NIP',
            'npwp' => 'NPWP',
            'gender' => 'GENDER',
            'position' => 'JABATAN',
            'work_assignment' => 'UNIT PENEMPATAN',
            'employment_status' => 'STATUS KERJA',
            'batch' => 'BATCH',
            'last_education' => 'PENDIDIKAN',
            'title_education' => 'GELAR',
            'major_education' => 'JURUSAN',
            'place_birthday' => 'TEMPAT LAHIR',
            'date_birthday' => 'TGL LAHIR',
            'age' => 'UMUR',
            'religion' => 'AGAMA',
            'marital_status' => 'STATUS NIKAH',
            'clothing_size' => 'UK. BAJU',
            'shoe_size' => 'UK. SEPATU',
            'payroll_bank_name' => 'NAMA BANK',
            'payroll_bank_account_number' => 'NOMOR REKENING',
            'payroll_bank_account_name' => 'NAMA PEMILIK REKENING',
            'province' => 'PROVINSI',
            'regency' => 'KABUPATEN',
            'district' => 'KECAMATAN',
            'village' => 'DESA',
            'address' => 'ALAMAT JALAN',
            'gps_coordinates' => 'KOORDINAT GPS'
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($user): array
    {
        $p = $user->person;

        return collect($this->columns)->map(function ($col) use ($user, $p) {
            return match ($col) {
                'email' => $user->email,
                'phone' => $user->phone,
                'status_user' => strtoupper($user->status_user),
                'role' => $user->role->name_role ?? '-',
                'name' => $p?->name ?? '-',
                'nik' => $p?->nik ?? '-',
                'no_kk' => $p?->no_kk ?? '-',
                'nip' => $p?->nip ?? '-',
                'npwp' => $p?->npwp ?? '-',
                'gender' => $p?->gender ?? '-',
                'position' => $p?->position?->name_position ?? '-',
                'work_assignment' => $p?->workAssignment?->sppgUnit?->name ?? '-',
                'employment_status' => $p?->employment_status ?? '-',
                'batch' => $p?->batch ?? '-',
                'last_education' => $p?->last_education ?? '-',
                'title_education' => $p?->title_education ?? '-',
                'major_education' => $p?->major_education ?? '-',
                'place_birthday' => $p?->place_birthday ?? '-',
                'date_birthday' => $p?->date_birthday ?? '-',
                'age' => $p?->age ?? '-',
                'religion' => $p?->religion ?? '-',
                'marital_status' => $p?->marital_status ?? '-',
                'clothing_size' => $p?->clothing_size ?? '-',
                'shoe_size' => $p?->shoe_size ?? '-',
                'payroll_bank_name' => $p?->payroll_bank_name ?? '-',
                'payroll_bank_account_number' => $p?->payroll_bank_account_number ?? '-',
                'payroll_bank_account_name' => $p?->payroll_bank_account_name ?? '-',
                'province' => $p?->province ?? '-',
                'regency' => $p?->regency ?? '-',
                'district' => $p?->district ?? '-',
                'village' => $p?->village ?? '-',
                'address' => $p?->address ?? '-',
                'gps_coordinates' => $p?->gps_coordinates ?? '-',
                default => '-',
            };
        })->toArray();
    }

    /**
     * Styling Judul Kolom (Baris 1): Huruf Tebal.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Menambahkan fitur Filter Otomatis pada judul kolom.
     */
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
