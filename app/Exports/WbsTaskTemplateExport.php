<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * The downloadable task template: a "Tasks" sheet (optionally pre-filled from the
 * project's selected template) plus an "Instructions" sheet.
 */
class WbsTaskTemplateExport implements WithMultipleSheets
{
    /** @param array<int, array<int, mixed>> $rows Pre-filled rows (already in column order). */
    public function __construct(private array $rows = [], private bool $prefilled = false)
    {
    }

    public function sheets(): array
    {
        return [
            new WbsTaskTemplateSheet($this->rows),
            new WbsTaskInstructionsSheet($this->prefilled),
        ];
    }
}
