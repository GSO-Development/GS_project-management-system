<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WbsTaskInstructionsSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private bool $prefilled = false)
    {
    }

    public function title(): string
    {
        return 'Instructions';
    }

    public function array(): array
    {
        $lines = [
            ['How to fill this template'],
            [''],
            [$this->prefilled
                ? 'The Tasks sheet is pre-filled from the template selected for this project. Edit, add or delete rows as needed.'
                : 'The Tasks sheet contains numbered empty rows. Type a Task Name next to each number you want to use. Rows without a Task Name are ignored.'],
            ['Uploading this file REPLACES the current task list of the project with the tasks in this file.'],
            [''],
            ['Column', 'Rules'],
            ['WBS No (required)', 'Hierarchy comes from the number: 1 = main task / phase, 1.1 and 1.2 = its subtasks, 1.1.1 = a sub-subtask. A parent must exist before its children.'],
            ['Task Name (required)', 'Up to 255 characters.'],
            ['Type', 'Optional. Phase, Task, Subtask or Milestone. Leave blank to detect automatically from the WBS No.'],
            ['Assigned To (Email)', 'Optional. Email of a member of this project.'],
            ['Start / End Date', 'Optional. Format YYYY-MM-DD, e.g. 2026-10-27. End Date must not be before Start Date.'],
            ['Start / End Time', 'Optional. 24-hour HH:MM, e.g. 08:30 or 17:30.'],
            ['Duration (Days)', 'Optional whole number. If Start Date is given and End Date is empty, End Date = Start Date + Duration - 1.'],
            ['Status', 'Pick from the list. Blank = Not Started.'],
            ['Priority', 'Pick from the list. Blank = Medium.'],
            ['Progress %', 'Number from 0 to 100. Parent tasks are calculated automatically from their subtasks.'],
            ['Dependencies', 'Optional. WBS numbers of predecessor tasks separated by commas, e.g. 1.1, 2.'],
            [''],
            ['Do not rename or remove the column headings in row 1 of the Tasks sheet.'],
        ];

        return $lines;
    }

    public function columnWidths(): array
    {
        return ['A' => 26, 'B' => 110];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('B1:B30')->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle('A1:A30')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'C3122E']]],
            6 => ['font' => ['bold' => true]],
        ];
    }
}
