<?php

namespace App\Exports;

use App\Models\Supplier;
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

class SupplierExport extends DefaultValueBinder implements
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
        return Supplier::query()->with('sppgUnits');
    }

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
            'type_supplier' => 'JENIS SUPPLIER',
            'name_supplier' => 'NAMA SUPPLIER',
            'leader_name'   => 'NAMA PIMPINAN',
            'phone'         => 'TELEPON',
            'commodities'   => 'KOMODITAS',
            'province'      => 'PROVINSI',
            'regency'       => 'KABUPATEN',
            'district'      => 'KECAMATAN',
            'village'       => 'DESA/KELURAHAN',
            'address'       => 'ALAMAT JALAN',
            'postal_code'   => 'KODE POS',
            'linked_sppg'   => 'UNIT SPPG TERKAIT',
        ];

        return collect($this->columns)->map(function ($col) use ($mappingNames) {
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($supplier): array
    {
        return collect($this->columns)->map(function ($col) use ($supplier) {
            return match ($col) {
                'type_supplier' => $supplier->type_supplier ?? '-',
                'name_supplier' => $supplier->name_supplier ?? '-',
                'leader_name'   => $supplier->leader_name ?? '-',
                'phone'         => $supplier->phone ?? '-',
                'commodities'   => $supplier->commodities ?? '-',
                'province'      => $supplier->province ?? '-',
                'regency'       => $supplier->regency ?? '-',
                'district'      => $supplier->district ?? '-',
                'village'       => $supplier->village ?? '-',
                'address'       => $supplier->address ?? '-',
                'postal_code'   => $supplier->postal_code ?? '-',
                'linked_sppg'   => $supplier->sppgUnits->pluck('name')->join(', ') ?: '-',
                default         => '-',
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
