<?php

use App\Exports\WbsTaskTemplateExport;
use App\Livewire\WbsTree;
use App\Models\Project;
use App\Models\ProjectTemplate;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function importProject(string $code, array $project = []): array
{
    $subsidiary = Subsidiary::create(['name' => "Import {$code}", 'code' => $code]);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create($project + [
        'name' => "Import Project {$code}", 'code' => "{$code}-001", 'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id, 'created_by' => $pm->id, 'pm_accepted' => true, 'status' => 'in_progress',
    ]);

    return [$project, $pm, $subsidiary];
}

/** Builds an .xlsx in the standard template layout. Each row follows the template column order. */
function templateFile(array $rows, ?array $headings = null): UploadedFile
{
    $headings ??= array_values(\App\Services\WbsTaskImportService::HEADERS);
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    foreach ($headings as $column => $heading) {
        $sheet->setCellValueExplicit([$column + 1, 1], $heading, DataType::TYPE_STRING);
    }
    foreach ($rows as $rowIndex => $row) {
        foreach ($row as $column => $value) {
            if ($value === null) continue;
            $sheet->setCellValueExplicit([$column + 1, $rowIndex + 2], $value, is_int($value) ? DataType::TYPE_NUMERIC : DataType::TYPE_STRING);
        }
    }
    $path = tempnam(sys_get_temp_dir(), 'tpl-').'.xlsx';
    (new Xlsx($spreadsheet))->save($path);
    $file = UploadedFile::fake()->createWithContent('tasks.xlsx', file_get_contents($path));
    @unlink($path);

    return $file;
}

/** Shortcut: [number, name, type, assignee, startDate, startTime, endDate, endTime, duration, status, priority, progress, deps] */
function taskRow(string $number, ?string $name = null, array $extra = []): array
{
    return array_replace([$number, $name, null, null, null, null, null, null, null, null, null, null, null], $extra);
}

it('downloads the default numbered template when the project has no template', function () {
    [$project, $pm] = importProject('DLD');

    Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('downloadImportTemplate')
        ->assertFileDownloaded('import-project-dld-task-template.xlsx');

    $binary = Excel::raw(new WbsTaskTemplateExport(), ExcelType::XLSX);
    $file = UploadedFile::fake()->createWithContent('default.xlsx', $binary);
    $sheets = Excel::toArray(new \App\Imports\TaskFileImport(), $file);

    expect($sheets)->toHaveCount(2)
        ->and($sheets[0][0])->toBe(array_values(\App\Services\WbsTaskImportService::HEADERS))
        ->and(array_column(array_slice($sheets[0], 1, 4), 0))->toBe(['1', '1.1', '1.2', '1.3'])
        ->and(\App\Services\WbsTaskImportService::HEADERS)->not->toHaveKey('estimated_hours')
        ->and(\App\Services\WbsTaskImportService::HEADERS)->not->toHaveKey('description');
});

it('pre-fills the download from the template selected for the project', function () {
    $template = ProjectTemplate::create(['name' => 'Delivery Template']);
    $day1 = $template->tasks()->create(['name' => 'Day 1', 'duration' => 1, 'unit' => 'days', 'order_index' => 0]);
    $day1->subtasks()->create(['project_template_id' => $template->id, 'name' => 'Kick-off', 'duration' => 2, 'unit' => 'days', 'order_index' => 0]);
    $day1->subtasks()->create(['project_template_id' => $template->id, 'name' => 'Setup', 'duration' => 1, 'unit' => 'weeks', 'order_index' => 1]);
    $template->tasks()->create(['name' => 'Day 2', 'duration' => 1, 'unit' => 'days', 'order_index' => 1]);
    [$project, $pm] = importProject('DLT', ['template_id' => $template->id]);

    Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('downloadImportTemplate')
        ->assertFileDownloaded('import-project-dlt-task-template.xlsx');

    $rows = (new \App\Services\WbsTaskImportService())->templateRows($template->load('tasks'));
    expect(array_map(fn ($row) => [$row[0], $row[1], $row[2], $row[8]], $rows))->toBe([
        ['1', 'Day 1', 'Phase', 1],
        ['1.1', 'Kick-off', 'Subtask', 2],
        ['1.2', 'Setup', 'Subtask', 7],
        ['2', 'Day 2', 'Task', 1],
    ]);

    // The pre-filled workbook can be uploaded back unchanged.
    $file = UploadedFile::fake()->createWithContent('prefilled.xlsx', Excel::raw(new WbsTaskTemplateExport($rows, true), ExcelType::XLSX));
    $parsed = (new \App\Services\WbsTaskImportService())->parse($file, $project);
    expect($parsed['errors'])->toBe([])->and($parsed['rows'])->toHaveCount(4);
});

it('imports a filled template with hierarchy, schedule and all columns, replacing existing tasks', function () {
    [$project, $pm, $subsidiary] = importProject('REP');
    $assignee = User::factory()->create(['subsidiary_id' => $subsidiary->id, 'email' => 'owner@example.com']);
    $project->members()->attach($assignee->id, ['role' => 'member']);

    $old = WbsItem::create(['project_id' => $project->id, 'wbs_code' => '1', 'item_type' => 'task', 'title' => 'Old task', 'status' => 'not_started', 'priority' => 'medium', 'progress' => 0, 'sort_order' => 1, 'created_by' => $pm->id]);

    $file = templateFile([
        taskRow('1', 'Day 1'),
        taskRow('1.1', 'Morning work', [3 => 'owner@example.com', 4 => '2026-10-26', 5 => '08:30', 6 => '2026-10-26', 7 => '12:30', 9 => 'In Progress', 10 => 'High', 11 => 40]),
        taskRow('1.2', 'Afternoon work', [4 => '2026-10-27', 8 => 2, 9 => 'Not Started']),
        taskRow('2', 'Day 2', [2 => 'Milestone', 4 => '2026-11-02', 6 => '2026-11-02']),
        taskRow('1.10', 'Later work', [12 => '1.1, 2']),
    ]);

    Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', $file)
        ->assertSet('taskImportErrors', [])
        ->assertCount('taskImportPreview', 5)
        ->call('requestImportConfirmation')
        ->assertSet('showReplaceConfirm', true)
        ->call('importTasks')
        ->assertSet('showImportModal', false);

    expect(WbsItem::find($old->id))->toBeNull()
        ->and(WbsItem::withTrashed()->find($old->id)->trashed())->toBeTrue();

    $items = WbsItem::where('project_id', $project->id)->get()->keyBy('title');
    expect($items)->toHaveCount(5);

    $day1 = $items['Day 1'];
    $morning = $items['Morning work'];
    expect($day1->item_type->value)->toBe('phase')
        ->and($day1->parent_id)->toBeNull()
        ->and($morning->parent_id)->toBe($day1->id)
        ->and($morning->item_type->value)->toBe('subtask')
        ->and($morning->assigned_user_id)->toBe($assignee->id)
        ->and($morning->start_date->toDateString())->toBe('2026-10-26')
        ->and($morning->start_time)->toBe('08:30:00')
        ->and($morning->end_time)->toBe('12:30:00')
        ->and($morning->status->value)->toBe('in_progress')
        ->and($morning->priority->value)->toBe('high')
        ->and($morning->progress)->toBe(40)
        ->and($items['Afternoon work']->end_date->toDateString())->toBe('2026-10-28') // start + duration - 1
        ->and($items['Day 2']->is_milestone)->toBeTrue()
        ->and($day1->start_date->toDateString())->toBe('2026-10-26')   // derived from children
        ->and($day1->progress)->toBe(13)                               // rolled up from the subtasks
        ->and($items['Later work']->wbs_code)->toBe('1.3');            // renumbered by position

    // Dependencies: "1.1, 2" on the last task (WBS 1.10 in the file)
    $later = $items['Later work'];
    expect($later->predecessors()->count())->toBe(2);
});

it('keeps tasks whose WBS number would look like a decimal (1.10) as text', function () {
    [$project, $pm] = importProject('DEC');
    $rows = [taskRow('1', 'Parent')];
    foreach (range(1, 10) as $n) $rows[] = taskRow("1.{$n}", "Child {$n}");

    Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', templateFile($rows))
        ->assertSet('taskImportErrors', [])
        ->call('requestImportConfirmation')
        ->call('importTasks');

    $parent = WbsItem::where('project_id', $project->id)->where('title', 'Parent')->firstOrFail();
    expect(WbsItem::where('parent_id', $parent->id)->count())->toBe(10)
        ->and(WbsItem::where('project_id', $project->id)->where('title', 'Child 10')->value('wbs_code'))->toBe('1.10');
});

it('ignores numbered empty rows from the default template', function () {
    [$project, $pm] = importProject('EMP');
    $rows = [taskRow('1', 'Only phase'), taskRow('1.1'), taskRow('1.2'), taskRow('2')];

    Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', templateFile($rows))
        ->assertSet('taskImportErrors', [])
        ->assertCount('taskImportPreview', 1);
});

it('reports exact row errors and blocks the import', function () {
    [$project, $pm] = importProject('ERR');
    $old = WbsItem::create(['project_id' => $project->id, 'wbs_code' => '1', 'item_type' => 'task', 'title' => 'Existing', 'status' => 'not_started', 'priority' => 'medium', 'progress' => 0, 'sort_order' => 1, 'created_by' => $pm->id]);

    $file = templateFile([
        taskRow('1', 'Phase'),
        taskRow('1.1', 'Bad dates', [4 => '2026-10-30', 6 => '2026-10-01']),
        taskRow('1.2', 'Stranger', [3 => 'nobody@example.com']),
        taskRow('1.3', 'Bad status', [9 => 'Almost done', 11 => 150]),
        taskRow('3.1', 'Orphan child'),
        taskRow('abc', 'Bad number'),
        taskRow('1', 'Duplicate number'),
    ]);

    $component = Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', $file)
        ->call('requestImportConfirmation')
        ->assertSet('showReplaceConfirm', false);

    $errors = implode("\n", $component->get('taskImportErrors'));
    expect($errors)->toContain('Row 3 (1.1 "Bad dates"): End Date must be on or after Start Date.')
        ->and($errors)->toContain('Row 4 (1.2 "Stranger"): \'nobody@example.com\' is not a member of this project.')
        ->and($errors)->toContain("Status 'Almost done' is not valid")
        ->and($errors)->toContain("Progress '150' must be a number between 0 and 100.")
        ->and($errors)->toContain('Parent task 3 is missing')
        ->and($errors)->toContain("WBS No 'abc' is invalid")
        ->and($errors)->toContain('WBS No 1 is already used in row 2.');

    $component->call('importTasks');
    expect(WbsItem::where('project_id', $project->id)->pluck('title')->all())->toBe(['Existing'])
        ->and(WbsItem::withTrashed()->find($old->id)->trashed())->toBeFalse();
});

it('does not import before the replacement is confirmed', function () {
    [$project, $pm] = importProject('CNF');

    Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', templateFile([taskRow('1', 'Needs confirmation')]))
        ->call('importTasks')
        ->assertSet('showImportModal', true);

    expect(WbsItem::where('project_id', $project->id)->count())->toBe(0);
});

it('rejects a file that is not the task template', function () {
    [$project, $pm] = importProject('BAD');

    $component = Livewire::actingAs($pm)->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', templateFile([['a', 'b']], ['Foo', 'Bar']));

    expect(implode(' ', $component->get('taskImportErrors')))->toContain('Required column(s) missing');
    $component->call('requestImportConfirmation')->assertSet('showReplaceConfirm', false);
});

it('assigns nobody when the importer cannot assign tasks', function () {
    [$project, $pm, $subsidiary] = importProject('NAS');
    $assignee = User::factory()->create(['subsidiary_id' => $subsidiary->id, 'email' => 'member@example.com']);
    $project->members()->attach($assignee->id, ['role' => 'member']);

    $rows = (new \App\Services\WbsTaskImportService())->parse(
        templateFile([taskRow('1', 'Assigned', [3 => 'member@example.com'])]),
        $project
    );
    expect($rows['errors'])->toBe([]);

    (new \App\Services\WbsTaskImportService())->replace($project, $rows['rows'], $pm, false);

    expect(WbsItem::where('project_id', $project->id)->value('assigned_user_id'))->toBeNull();
});

it('still parses optional description and estimated hours if extra columns are provided in a custom file', function () {
    [$project, $pm] = importProject('LEG');
    $file = templateFile(
        [['1', 'Task with extra', 'Task', null, null, null, null, null, 1, 'Not Started', 'Medium', 0, null, 6.5, 'Some extra note']],
        array_merge(array_values(\App\Services\WbsTaskImportService::HEADERS), ['Estimated Hours', 'Description'])
    );
    $parsed = (new \App\Services\WbsTaskImportService())->parse($file, $project);
    expect($parsed['errors'])->toBe([])
        ->and($parsed['rows'][0]['estimated_hours'])->toBe(6.5)
        ->and($parsed['rows'][0]['description'])->toBe('Some extra note');
});
