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
            'socialMedia'
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
            'id_sppg_unit' => 'ID SPPG',
            'code_sppg_unit' => 'KODE SPPG',
            'name' => 'NAMA SPPG',
            'status' => 'STATUS',
            'operational_date' => 'TANGGAL OPERASIONAL',
            'leader_name' => 'NAMA KEPALA SPPG',
            'province' => 'PROVINSI',
            'regency' => 'KABUPATEN',
            'district' => 'KECAMATAN',
            'village' => 'DESA/KELURAHAN',
            'address' => 'ALAMAT LENGKAP',
            'latitude_gps' => 'LATITUDE GPS',
            'longitude_gps' => 'LONGITUDE GPS',
            'facebook_url' => 'LINK FACEBOOK',
            'instagram_url' => 'LINK INSTAGRAM',
            'tiktok_url' => 'LINK TIKTOK',
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($sppgUnit): array
    {
        $leader = $sppgUnit->leader;
        $sm = $sppgUnit->socialMedia;

        return collect($this->columns)->map(function ($col) use ($sppgUnit, $leader, $sm) {
            return match ($col) {
                'id_sppg_unit' => $sppgUnit->id_sppg_unit ?? '-',
                'code_sppg_unit' => $sppgUnit->code_sppg_unit ?? '-',
                'name' => $sppgUnit->name ?? '-',
                'status' => $sppgUnit->status ?? '-',
                'operational_date' => $sppgUnit->operational_date ?? '-',
                'leader_name' => collect([$leader?->title_education_front, $leader?->name, $leader?->title_education])->filter()->join(' '), 
                'province' => $sppgUnit->province ?? '-',
                'regency' => $sppgUnit->regency ?? '-',
                'district' => $sppgUnit->district ?? '-',
                'village' => $sppgUnit->village ?? '-',
                'address' => $sppgUnit->address ?? '-',
                'latitude_gps' => $sppgUnit->latitude_gps ?? '-',
                'longitude_gps' => $sppgUnit->longitude_gps ?? '-',
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
