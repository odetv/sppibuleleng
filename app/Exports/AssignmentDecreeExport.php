<?php

namespace App\Exports;

use App\Models\AssignmentDecree;
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
use Illuminate\Support\Facades\Storage;

class AssignmentDecreeExport extends DefaultValueBinder implements
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
        return AssignmentDecree::query()->with(['workAssignments.sppgUnit']);
    }

    public function bindValue(Cell $cell, $value)
    {
        // Menangani angka unik panjang yang bisa diubah Excel menjadi E+
        if (is_numeric($value) && strlen($value) > 10) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function headings(): array
    {
         $mappingNames = [
            'no_sk' => 'NOMOR SK',
            'date_sk' => 'TANGGAL SK',
            'no_ba_verval' => 'NOMOR BA VERVAL',
            'date_ba_verval' => 'TANGGAL BA VERVAL',
            'sppg_unit_ids' => 'ID SPPG TERKAIT',
            'sppg_unit_names' => 'NAMA SPPG',
            'file_sk_link' => 'LINK FILE SK',
        ];

        return collect($this->columns)->filter(fn($col) => $col !== 'id_assignment_decree')->map(function ($col) use ($mappingNames) {
            
            // Handle custom split columns that might not exist in original `columns` request directly
            // For simplicity, we just inject them manually replacing the old `sppg_units` request
            return $mappingNames[$col] ?? strtoupper(str_replace('_', ' ', $col));
        })->toArray();
    }

    public function map($decree): array
    {
        $sppgIds = $decree->workAssignments->map(function ($wa) {
            return $wa->id_sppg_unit;
        })->implode(', ');
        
        $sppgNames = $decree->workAssignments->map(function ($wa) {
            return $wa->sppgUnit->name ?? 'Unknown';
        })->implode(', ');

        $firstWA = $decree->workAssignments->first();
        $fileUrl = '-';
        if ($firstWA && $decree->file_sk) {
            $sppgHash = md5($firstWA->id_sppg_unit . config('app.key'));
            $skHash = md5($decree->id_assignment_decree . config('app.key'));
            $fileUrl = url(Storage::url("sppgunits/{$sppgHash}/files/{$skHash}/{$decree->file_sk}"));
        }

        // Return standard array matching headings exactly
        $row = [];
        foreach($this->columns as $col) {
            if ($col === 'id_assignment_decree') continue;
            
            if ($col === 'no_sk') $row[] = $decree->no_sk ?? '-';
            elseif ($col === 'date_sk') $row[] = $decree->date_sk ? \Carbon\Carbon::parse($decree->date_sk)->format('d-m-Y') : '-';
            elseif ($col === 'no_ba_verval') $row[] = $decree->no_ba_verval ?? '-';
            elseif ($col === 'date_ba_verval') $row[] = $decree->date_ba_verval ? \Carbon\Carbon::parse($decree->date_ba_verval)->format('d-m-Y') : '-';
            elseif ($col === 'sppg_unit_ids') $row[] = $sppgIds ?: '-';
            elseif ($col === 'sppg_unit_names') $row[] = $sppgNames ?: '-';
            elseif ($col === 'file_sk_link') $row[] = $fileUrl;
            else $row[] = '-';
        }

        return $row;
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
