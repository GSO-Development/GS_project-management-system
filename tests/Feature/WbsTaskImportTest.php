<?php

use App\Livewire\WbsTree;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

it('previews and imports tasks from a csv file', function () {
    $subsidiary = Subsidiary::create(['name' => 'Import Subsidiary', 'code' => 'IMP']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $assignee = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Import Project',
        'code' => 'IMP-001',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'created_by' => $pm->id,
        'pm_accepted' => true,
        'status' => 'in_progress',
    ]);
    $file = UploadedFile::fake()->createWithContent(
        'tasks.csv',
        "title,description,assignee_email,start_date,end_date,status,priority,progress,estimated_hours\n" .
        "Requirements,Confirm scope,{$assignee->email},2026-10-24,2026-10-25,not_started,high,0,8\n" .
        "Delivery,Release output,{$assignee->email},2026-10-26,2026-10-28,in_progress,medium,20,16\n"
    );

    Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', $file)
        ->call('previewTaskImport')
        ->assertSet('taskImportPreview.0.title', 'Requirements')
        ->assertSet('taskImportPreview.1.title', 'Delivery')
        ->call('importTasks')
        ->assertSet('showImportModal', false);

    $this->assertDatabaseHas('wbs_items', [
        'project_id' => $project->id,
        'title' => 'Requirements',
        'assigned_user_id' => $assignee->id,
        'priority' => 'high',
    ]);
    $this->assertDatabaseHas('wbs_items', [
        'project_id' => $project->id,
        'title' => 'Delivery',
        'progress' => 20,
    ]);
});

it('previews task list paragraphs from a docx file', function () {
    $subsidiary = Subsidiary::create(['name' => 'Word Subsidiary', 'code' => 'WRD']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Word Import Project', 'code' => 'WRD-001',
        'subsidiary_id' => $subsidiary->id, 'project_manager_id' => $pm->id,
        'created_by' => $pm->id, 'pm_accepted' => true, 'status' => 'in_progress',
    ]);
    $path = tempnam(sys_get_temp_dir(), 'task-docx-');
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>Task list</w:t></w:r></w:p><w:p><w:r><w:t>Collect requirements</w:t></w:r></w:p><w:p><w:r><w:t>Prepare release</w:t></w:r></w:p></w:body></w:document>');
    $zip->close();
    $file = UploadedFile::fake()->createWithContent('tasks.docx', file_get_contents($path));

    Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', $file)
        ->call('previewTaskImport')
        ->assertSet('taskImportPreview.0.title', 'Collect requirements')
        ->assertSet('taskImportPreview.1.title', 'Prepare release');
});

it('skips long document prose instead of causing a database error', function () {
    $subsidiary = Subsidiary::create(['name' => 'Safe Word Subsidiary', 'code' => 'SWR']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Safe Word Import', 'code' => 'SWR-001',
        'subsidiary_id' => $subsidiary->id, 'project_manager_id' => $pm->id,
        'created_by' => $pm->id, 'pm_accepted' => true, 'status' => 'in_progress',
    ]);
    $path = tempnam(sys_get_temp_dir(), 'safe-docx-');
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $longProse = str_repeat('This is explanatory text and not a task. ', 12);
    $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>'.htmlspecialchars($longProse, ENT_XML1).'</w:t></w:r></w:p><w:p><w:r><w:t>Valid short task</w:t></w:r></w:p></w:body></w:document>');
    $zip->close();
    $file = UploadedFile::fake()->createWithContent('safe-tasks.docx', file_get_contents($path));

    Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', $file)
        ->call('previewTaskImport')
        ->assertCount('taskImportPreview', 1)
        ->assertSet('taskImportPreview.0.title', 'Valid short task')
        ->call('importTasks');

    $this->assertDatabaseHas('wbs_items', ['project_id' => $project->id, 'title' => 'Valid short task']);
});

it('maps a structured word table into task fields', function () {
    $subsidiary = Subsidiary::create(['name' => 'Structured Word', 'code' => 'STW']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $assignee = User::factory()->create(['subsidiary_id' => $subsidiary->id, 'name' => 'Task Owner']);
    $project = Project::create([
        'name' => 'Structured Import', 'code' => 'STW-001',
        'subsidiary_id' => $subsidiary->id, 'project_manager_id' => $pm->id,
        'created_by' => $pm->id, 'pm_accepted' => true, 'status' => 'in_progress',
    ]);
    $cell = fn (string $text) => '<w:tc><w:p><w:r><w:t>'.htmlspecialchars($text, ENT_XML1).'</w:t></w:r></w:p></w:tc>';
    $xml = '<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:tbl>'
        .'<w:tr>'.$cell('Task Name').$cell('Assigned To').$cell('Planned Start').$cell('Planned Finish').$cell('Progress').$cell('Status').'</w:tr>'
        .'<w:tr>'.$cell('Kick-Off Meeting').$cell('Task Owner').$cell('09 Jul 2026').$cell('10 Jul 2026').$cell('40%').$cell('In Progress').'</w:tr>'
        .'</w:tbl></w:body></w:document>';
    $path = tempnam(sys_get_temp_dir(), 'structured-docx-');
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('word/document.xml', $xml);
    $zip->close();
    $file = UploadedFile::fake()->createWithContent('structured.docx', file_get_contents($path));

    Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', $file)
        ->call('previewTaskImport')
        ->assertSet('taskImportPreview.0.title', 'Kick-Off Meeting')
        ->assertSet('taskImportPreview.0.start_date', '2026-07-09')
        ->assertSet('taskImportPreview.0.end_date', '2026-07-10')
        ->assertSet('taskImportPreview.0.progress', 40)
        ->call('importTasks');

    $this->assertDatabaseHas('wbs_items', [
        'project_id' => $project->id, 'title' => 'Kick-Off Meeting',
        'assigned_user_id' => $assignee->id, 'status' => 'in_progress', 'progress' => 40,
    ]);
    $task = \App\Models\WbsItem::where('project_id', $project->id)->where('title', 'Kick-Off Meeting')->firstOrFail();
    expect($task->start_date->toDateString())->toBe('2026-07-09')
        ->and($task->end_date->toDateString())->toBe('2026-07-10');
});

it('maps start schedule and target deadline word headers', function () {
    $subsidiary = Subsidiary::create(['name' => 'Schedule Headers', 'code' => 'SCH']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Schedule Import', 'code' => 'SCH-001',
        'subsidiary_id' => $subsidiary->id, 'project_manager_id' => $pm->id,
        'created_by' => $pm->id, 'pm_accepted' => true, 'status' => 'in_progress',
    ]);
    $cell = fn (string $text) => '<w:tc><w:p><w:r><w:t>'.htmlspecialchars($text, ENT_XML1).'</w:t></w:r></w:p></w:tc>';
    $xml = '<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:tbl>'
        .'<w:tr>'.$cell('Task Deliverable').$cell('Start Schedule').$cell('Target Deadline').'</w:tr>'
        .'<w:tr>'.$cell('Configure Server').$cell('13 Jul 2026').$cell('20 Jul 2026').'</w:tr>'
        .'</w:tbl></w:body></w:document>';
    $path = tempnam(sys_get_temp_dir(), 'schedule-docx-');
    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('word/document.xml', $xml);
    $zip->close();

    Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->call('openImportModal')
        ->set('taskImportFile', UploadedFile::fake()->createWithContent('schedule.docx', file_get_contents($path)))
        ->call('previewTaskImport')
        ->assertSet('taskImportPreview.0.start_date', '2026-07-13')
        ->assertSet('taskImportPreview.0.end_date', '2026-07-20')
        ->call('importTasks');

    $task = \App\Models\WbsItem::where('project_id', $project->id)->firstOrFail();
    expect($task->start_date->toDateString())->toBe('2026-07-13');
});
