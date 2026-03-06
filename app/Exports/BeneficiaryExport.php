<?php

namespace App\Exports;

use App\Models\Beneficiary;
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

class BeneficiaryExport extends DefaultValueBinder implements
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
        return Beneficiary::query()->with('sppgUnit');
    }

    public function bindValue(Cell $cell, $value)
    {
        // Handle long numbers
        if (is_numeric($value) && strlen($value) > 10) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function headings(): array
    {
        $mappingNames = [
            'group_type' => 'TIPE KELOMPOK',
            'category' => 'KATEGORI',
            'code' => 'KODE PM',
            'name' => 'NAMA PENERIMA MANFAAT',
            'pic_name' => 'NAMA PIC',
            'pic_phone' => 'NO TELEPON PIC',
            'pic_email' => 'EMAIL PIC',
            'province' => 'PROVINSI',
            'regency' => 'KABUPATEN/KOTA',
            'district' => 'KECAMATAN',
            'village' => 'DESA/KELURAHAN',
            'address' => 'ALAMAT JALAN',
            'latitude_gps' => 'LATITUDE GPS',
            'longitude_gps' => 'LONGITUDE GPS',
            'small_portion_male' => 'PORSI KECIL LAKI-LAKI',
            'small_portion_female' => 'PORSI KECIL PEREMPUAN',
            'large_portion_male' => 'PORSI BESAR LAKI-LAKI',
            'large_portion_female' => 'PORSI BESAR PEREMPUAN',
            'teacher_portion' => 'PORSI GURU',
            'staff_portion' => 'PORSI TENAGA KEPENDIDIKAN',
            'cadre_portion' => 'PORSI KADER',
            'id_sppg_unit' => 'ID SPPG (PENUGASAN)',
            'sppg_unit_name' => 'NAMA SPPG (PENUGASAN)'
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($beneficiary): array
    {
        return collect($this->columns)->map(function ($col) use ($beneficiary) {
            return match ($col) {
                'group_type' => $beneficiary->group_type ?? '-',
                'category' => $beneficiary->category ?? '-',
                'code' => $beneficiary->code ?? '-',
                'name' => $beneficiary->name ?? '-',
                'pic_name' => $beneficiary->pic_name ?? '-',
                'pic_phone' => $beneficiary->pic_phone ?? '-',
                'pic_email' => $beneficiary->pic_email ?? '-',
                'province' => $beneficiary->province ?? '-',
                'regency' => $beneficiary->regency ?? '-',
                'district' => $beneficiary->district ?? '-',
                'village' => $beneficiary->village ?? '-',
                'address' => $beneficiary->address ?? '-',
                'latitude_gps' => $beneficiary->latitude_gps ?? '-',
                'longitude_gps' => $beneficiary->longitude_gps ?? '-',
                'small_portion_male' => (string)($beneficiary->small_portion_male ?? 0),
                'small_portion_female' => (string)($beneficiary->small_portion_female ?? 0),
                'large_portion_male' => (string)($beneficiary->large_portion_male ?? 0),
                'large_portion_female' => (string)($beneficiary->large_portion_female ?? 0),
                'teacher_portion' => (string)($beneficiary->teacher_portion ?? 0),
                'staff_portion' => (string)($beneficiary->staff_portion ?? 0),
                'cadre_portion' => (string)($beneficiary->cadre_portion ?? 0),
                'id_sppg_unit' => $beneficiary->id_sppg_unit ?? '-',
                'sppg_unit_name' => $beneficiary->sppgUnit?->name ?? 'Belum Ditugaskan',
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
