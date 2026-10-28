<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

/**
 * Returns the raw rows of the task template. The first row holds the column
 * headings; validation and persistence live in WbsTaskImportService.
 */
class TaskFileImport implements ToArray
{
    public function array(array $array): void
    {
        // Rows are returned by Excel::toArray(); persistence is handled after the user confirms.
    }
}
