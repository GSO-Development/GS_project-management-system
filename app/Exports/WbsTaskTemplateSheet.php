<?php

namespace App\Exports;

use App\Services\WbsTaskImportService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WbsTaskTemplateSheet extends DefaultValueBinder implements
    FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithColumnFormatting, WithEvents, WithCustomValueBinder
{
    /** Number of rows (below the heading) that receive dropdowns and formats. */
    private const FORMATTED_ROWS = 500;

    /** @param array<int, array<int, mixed>> $rows */
    public function __construct(private array $rows = [])
    {
    }

    public function title(): string
    {
        return 'Tasks';
    }

    public function headings(): array
    {
        return array_values(WbsTaskImportService::HEADERS);
    }

    public function array(): array
    {
        if ($this->rows) return $this->rows;

        // Default format: numbered placeholders for 3 phases with 3 subtasks each.
        $rows = [];
        foreach ([1, 2, 3] as $phase) {
            $rows[] = [(string) $phase];
            foreach ([1, 2, 3] as $sub) $rows[] = ["{$phase}.{$sub}"];
        }
        return $rows;
    }

    /** WBS numbers, names and dependencies must stay text ("1.10" must not become 1.1). */
    public function bindValue(Cell $cell, $value): bool
    {
        if (in_array($cell->getColumn(), ['A', 'B', 'M'], true) && $cell->getRow() > 1 && $value !== null && $value !== '') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, 'B' => 42, 'C' => 13, 'D' => 30, 'E' => 14, 'F' => 12, 'G' => 14, 'H' => 12,
            'I' => 16, 'J' => 15, 'K' => 12, 'L' => 12, 'M' => 16,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => 'yyyy-mm-dd',
            'F' => 'hh:mm',
            'G' => 'yyyy-mm-dd',
            'H' => 'hh:mm',
            'M' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C3122E']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $last = self::FORMATTED_ROWS + 1;

                $sheet->freezePane('C2');
                $sheet->getRowDimension(1)->setRowHeight(26);

                // Apply the formats to the whole entry area, not only the pre-filled rows.
                foreach ($this->columnFormats() as $column => $format) {
                    $sheet->getStyle("{$column}2:{$column}{$last}")->getNumberFormat()->setFormatCode($format);
                }

                $this->dropdown($sheet, 'C', WbsTaskImportService::TYPE_LABELS, $last);
                $this->dropdown($sheet, 'J', WbsTaskImportService::STATUS_LABELS, $last);
                $this->dropdown($sheet, 'K', WbsTaskImportService::PRIORITY_LABELS, $last);
            },
        ];
    }

    private function dropdown(Worksheet $sheet, string $column, array $options, int $last): void
    {
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Invalid value');
        $validation->setError('Choose a value from the list.');
        $validation->setFormula1('"'.implode(',', $options).'"');

        $sheet->setDataValidation("{$column}2:{$column}{$last}", $validation);
    }
}
