<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaskFileImport implements ToArray, WithHeadingRow
{
    public function array(array $array): void
    {
        // Rows are returned by Excel::toArray(); persistence is handled after preview confirmation.
    }
}
