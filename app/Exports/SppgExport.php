<?php

namespace App\Exports;

use App\Models\SppgUnit;
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

class SppgExport extends DefaultValueBinder implements
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
        return SppgUnit::query()->with([
            'leader',
            'nutritionist',
            'accountant',
            'socialMedia',
            'workAssignments.decree'
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
            'sppg_leader'    => 'NAMA KEPALA SPPG (KASPPG)',
            'sppg_nutritionist' => 'NAMA AHLI GIZI SPPG (AG)',
            'sppg_accountant'   => 'NAMA AKUNTAN SPPG (AK)',
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
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($sppgUnit): array
    {
        $leader = $sppgUnit->leader;
        $nutritionist = $sppgUnit->nutritionist;
        $accountant = $sppgUnit->accountant;
        $sm = $sppgUnit->socialMedia;

        $skKasppg = null;
        $skAg = null;
        $skAk = null;
        $skKasppgSppgId = null;
        $skAgSppgId = null;
        $skAkSppgId = null;

        $unitWA = $sppgUnit->workAssignments;
        if ($unitWA) {
            foreach ($unitWA as $wa) {
                if ($wa->decree) {
                    if ($wa->decree->type_sk == 4) {
                        $skKasppg = $wa->decree;
                        $skKasppgSppgId = $wa->id_sppg_unit;
                    } elseif ($wa->decree->type_sk == 5) {
                        $skAg = $wa->decree;
                        $skAgSppgId = $wa->id_sppg_unit;
                    } elseif ($wa->decree->type_sk == 6) {
                        $skAk = $wa->decree;
                        $skAkSppgId = $wa->id_sppg_unit;
                    }
                }
            }
        }

        return collect($this->columns)->map(function ($col) use ($sppgUnit, $leader, $nutritionist, $accountant, $sm, $skKasppg, $skAg, $skAk, $skKasppgSppgId, $skAgSppgId, $skAkSppgId) {
            return match ($col) {
                'sppg_id' => $sppgUnit->id_sppg_unit ?? '-',
                'sppg_code' => $sppgUnit->code_sppg_unit ?? '-',
                'sppg_name' => $sppgUnit->name ?? '-',
                'sppg_status' => $sppgUnit->status ?? '-',
                'sppg_operational_date' => $sppgUnit->operational_date ?? '-',
                'sppg_province' => $sppgUnit->province ?? '-',
                'sppg_regency' => $sppgUnit->regency ?? '-',
                'sppg_district' => $sppgUnit->district ?? '-',
                'sppg_village' => $sppgUnit->village ?? '-',
                'sppg_address' => $sppgUnit->address ?? '-',
                'sppg_latitude' => $sppgUnit->latitude_gps ?? '-',
                'sppg_longitude' => $sppgUnit->longitude_gps ?? '-',
                'sppg_leader' => collect([$leader?->title_education_front, $leader?->name, $leader?->title_education])->filter()->join(' ') ?: '-',
                'sppg_nutritionist' => collect([$nutritionist?->title_education_front, $nutritionist?->name, $nutritionist?->title_education])->filter()->join(' ') ?: '-',
                'sppg_accountant' => collect([$accountant?->title_education_front, $accountant?->name, $accountant?->title_education])->filter()->join(' ') ?: '-',
                'sppg_photo' => $sppgUnit->photo ? asset('storage/' . $sppgUnit->photo) : '-',
                'sppg_facebook_url'  => $sm?->facebook_url ?? '-',
                'sppg_instagram_url' => $sm?->instagram_url ?? '-',
                'sppg_tiktok_url'    => $sm?->tiktok_url ?? '-',
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
