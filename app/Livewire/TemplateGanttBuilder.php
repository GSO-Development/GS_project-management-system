<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\ProjectTemplate;
use App\Models\TemplateTask;
use Illuminate\Support\Str;

class TemplateGanttBuilder extends Component
{
    public ProjectTemplate $template;
    public $tasks = [];
    public $setupDuration = 1;
    public $setupUnit = 'days';
    public $divideByWeeks = false;
    public $divideByDays = false;
    public bool $divideByHours = false;
    public $showSetupModal = false;
    public $isConfigured = false;

    // Level 4 working time config
    public string $workStartTime = '08:30';
    public string $workEndTime   = '17:30';
    public array  $workDays      = ['mon','tue','wed','thu','fri'];

    // Edit state
    public $editingTaskId = null;
    public $editingName = '';
    public $editingNewDuration = '';

    // Top Header Total Days Edit state
    public $unassignedDays = 0;
    public $isEditingTotalDays = false;
    public $editingTotalDaysValue = '';

    public function mount(ProjectTemplate $template)
    {
        if (!auth()->user()?->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only PMO Admins can access or build project templates.');
        }

        $this->template = $template;

        // Restore working-time config from template
        $this->divideByHours = (bool) $template->divide_by_hours;
        if ($template->work_start_time) {
            $this->workStartTime = substr($template->work_start_time, 0, 5);
        }
        if ($template->work_end_time) {
            $this->workEndTime = substr($template->work_end_time, 0, 5);
        }
        if ($template->work_days) {
            $this->workDays = $template->work_days;
        }

        $existingTasks = $this->template->tasks()->orderBy('order_index')->get();
        if ($existingTasks->count() > 0) {
            $this->isConfigured = true;
            // Determine setup unit from first non-hour task
            $firstNonHour = $existingTasks->firstWhere('unit', '!=', 'hours');
            $this->setupUnit = $firstNonHour ? $firstNonHour->unit : 'days';

            $levelMap = [];
            foreach ($existingTasks as $task) {
                $level = 1;
                if ($task->parent_id && isset($levelMap[$task->parent_id])) {
                    $level = $levelMap[$task->parent_id] + 1;
                }
                $levelMap[$task->id] = $level;

                $this->tasks[] = [
                    'id'         => (string) $task->id,
                    'parent_id'  => $task->parent_id ? (string) $task->parent_id : null,
                    'name'       => $task->name,
                    'duration'   => $task->duration,
                    'unit'       => $task->unit,
                    'is_deleted' => false,
                    'level'      => $level,
                    'is_expanded' => (bool) $task->is_expanded,
                ];
            }
        } else {
            $this->showSetupModal = true;
        }
    }

    protected function checkSuperAdmin()
    {
        if (!auth()->user()?->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only PMO Admins can configure or modify WBS templates.');
        }
    }

    public function generateTasks()
    {
        $this->checkSuperAdmin();

        $rules = [
            'setupDuration' => 'required|integer|min:1|max:1000',
            'setupUnit'     => 'required|in:days,weeks,months',
        ];

        if ($this->divideByHours) {
            $rules['workStartTime'] = 'required';
            $rules['workEndTime']   = 'required';

            // Validate end > start
            $start = Carbon::createFromFormat('H:i', $this->workStartTime);
            $end   = Carbon::createFromFormat('H:i', $this->workEndTime);
            if ($end->lessThanOrEqualTo($start)) {
                $this->addError('workEndTime', 'End Time must be after Start Time.');
                return;
            }
        }

        $this->validate($rules);

        $this->tasks    = [];
        $totalDays      = (int) $this->setupDuration;

        $this->generateHierarchicalTasks($totalDays);

        $this->isConfigured    = true;
        $this->showSetupModal  = false;
    }

    private function generateHierarchicalTasks($totalDays)
    {
        $mainIndex     = 1;
        $remainingDays = $totalDays;

        if ($this->setupUnit === 'days') {
            while ($remainingDays > 0) {
                $mainId       = 'temp_' . Str::random(8);
                $this->tasks[] = [
                    'id'         => $mainId,
                    'parent_id'  => null,
                    'name'       => 'Day ' . $mainIndex,
                    'duration'   => 1,
                    'unit'       => 'days',
                    'is_deleted' => false,
                    'level'      => 1,
                    'is_expanded' => $this->divideByHours,
                ];
                if ($this->divideByHours) {
                    $this->generateHourSlots($mainId, 2);
                }
                $remainingDays--;
                $mainIndex++;
            }
        } elseif ($this->setupUnit === 'weeks') {
            $chunkSize = 7;
            while ($remainingDays > 0) {
                $duration      = min($remainingDays, $chunkSize);
                $mainId        = 'temp_' . Str::random(8);
                $this->tasks[] = [
                    'id'         => $mainId,
                    'parent_id'  => null,
                    'name'       => 'Week ' . $mainIndex,
                    'duration'   => $duration,
                    'unit'       => 'days',
                    'is_deleted' => false,
                    'level'      => 1,
                    'is_expanded' => $this->divideByDays || $this->divideByHours,
                ];

                if ($this->divideByDays) {
                    $this->generateDaySubtasks($mainId, 'Week ' . $mainIndex, $duration, 2);
                } elseif ($this->divideByHours) {
                    // Hours directly under week (no day level)
                    $this->generateHourSlots($mainId, 2);
                }

                $remainingDays -= $duration;
                $mainIndex++;
            }
        } elseif ($this->setupUnit === 'months') {
            $chunkSize = 30;
            while ($remainingDays > 0) {
                $duration      = min($remainingDays, $chunkSize);
                $mainId        = 'temp_' . Str::random(8);
                $this->tasks[] = [
                    'id'         => $mainId,
                    'parent_id'  => null,
                    'name'       => 'Month ' . $mainIndex,
                    'duration'   => $duration,
                    'unit'       => 'days',
                    'is_deleted' => false,
                    'level'      => 1,
                    'is_expanded' => $this->divideByWeeks || $this->divideByDays || $this->divideByHours,
                ];

                if ($this->divideByWeeks) {
                    $subWeekRemaining = $duration;
                    $weekIndex        = 1;
                    while ($subWeekRemaining > 0) {
                        $weekDuration  = min($subWeekRemaining, 7);
                        $subId         = 'temp_' . Str::random(8);
                        $this->tasks[] = [
                            'id'         => $subId,
                            'parent_id'  => $mainId,
                            'name'       => 'Month ' . $mainIndex . ' - Week ' . $weekIndex,
                            'duration'   => $weekDuration,
                            'unit'       => 'days',
                            'is_deleted' => false,
                            'level'      => 2,
                            'is_expanded' => $this->divideByDays || $this->divideByHours,
                        ];

                        if ($this->divideByDays) {
                            $this->generateDaySubtasks($subId, 'M' . $mainIndex . 'W' . $weekIndex, $weekDuration, 3);
                        } elseif ($this->divideByHours) {
                            $this->generateHourSlots($subId, 3);
                        }

                        $subWeekRemaining -= $weekDuration;
                        $weekIndex++;
                    }
                } elseif ($this->divideByDays) {
                    $this->generateDaySubtasks($mainId, 'Month ' . $mainIndex, $duration, 2);
                } elseif ($this->divideByHours) {
                    $this->generateHourSlots($mainId, 2);
                }

                $remainingDays -= $duration;
                $mainIndex++;
            }
        }
    }

    /**
     * Generate Level 4 hour-slot child tasks.
    /**
     * Generate Level 4 hour-slot child tasks.
     * Default to 2 task slots: 08:30 AM – 12:30 PM (Morning) and 12:30 PM – 05:30 PM (Afternoon).
     */
    private function generateHourSlots(string $parentId, int $level): void
    {
        try {
            $start = Carbon::createFromFormat('H:i', $this->workStartTime);
            $end   = Carbon::createFromFormat('H:i', $this->workEndTime);
        } catch (\Throwable) {
            $start = Carbon::createFromFormat('H:i', '08:30');
            $end   = Carbon::createFromFormat('H:i', '17:30');
        }

        if ($end->lessThanOrEqualTo($start)) {
            return;
        }

        // Mid-day boundary at 12:30
        $mid = Carbon::createFromFormat('H:i', '12:30');
        if ($mid->lessThanOrEqualTo($start) || $mid->greaterThanOrEqualTo($end)) {
            $totalMins = $start->diffInMinutes($end);
            $mid = $start->copy()->addMinutes((int)($totalMins / 2));
        }

        $slots = [
            [
                'start'    => $start,
                'end'      => $mid,
                'duration' => max(1, round($start->diffInMinutes($mid) / 60)),
            ],
            [
                'start'    => $mid,
                'end'      => $end,
                'duration' => max(1, round($mid->diffInMinutes($end) / 60)),
            ],
        ];

        foreach ($slots as $slot) {
            $label = $slot['start']->format('h:i A') . ' – ' . $slot['end']->format('h:i A');

            $this->tasks[] = [
                'id'          => 'temp_' . Str::random(8),
                'parent_id'   => $parentId,
                'name'        => $label,
                'duration'    => $slot['duration'],
                'unit'        => 'hours',
                'is_deleted'  => false,
                'level'       => $level,
                'is_expanded' => false,
            ];
        }
    }

    private function generateDaySubtasks($parentId, $prefix, $days, $level)
    {
        for ($i = 1; $i <= $days; $i++) {
            $dayId         = 'temp_' . Str::random(8);
            $this->tasks[] = [
                'id'         => $dayId,
                'parent_id'  => $parentId,
                'name'       => $prefix . '.' . $i . '.0 Day ' . $i,
                'duration'   => 1,
                'unit'       => 'days',
                'is_deleted' => false,
                'level'      => $level,
                'is_expanded' => $this->divideByHours,
            ];
            if ($this->divideByHours) {
                $this->generateHourSlots($dayId, $level + 1);
            }
        }
    }

    public function toggleExpand($taskId)
    {
        $index = collect($this->tasks)->search(fn($t) => $t['id'] === $taskId);
        if ($index !== false) {
            $this->tasks[$index]['is_expanded'] = !$this->tasks[$index]['is_expanded'];
        }
    }

    public function editTotalDays()
    {
        $this->isEditingTotalDays = true;
        $this->editingTotalDaysValue = $this->setupDuration;
    }

    public function cancelEditTotalDays()
    {
        $this->isEditingTotalDays = false;
        $this->editingTotalDaysValue = '';
    }

    public function saveTotalDays()
    {
        $this->checkSuperAdmin();
        $this->validate([
            'editingTotalDaysValue' => 'required|numeric|min:1',
        ]);
        
        $newTotal = (int) $this->editingTotalDaysValue;
        $difference = $newTotal - $this->setupDuration;
        
        $this->setupDuration = $newTotal;
        $this->isEditingTotalDays = false;
        
        if ($difference > 0) {
            $this->unassignedDays += $difference;
        } elseif ($difference < 0) {
            $toSteal = abs($difference);
            
            if ($this->unassignedDays >= $toSteal) {
                $this->unassignedDays -= $toSteal;
            } else {
                $toSteal -= $this->unassignedDays;
                $this->unassignedDays = 0;
                $this->stealFromBottom($toSteal);
            }
        }
    }

    private function stealFromBottom($amountToSteal)
    {
        for ($i = count($this->tasks) - 1; $i >= 0; $i--) {
            if ($this->tasks[$i]['is_deleted'] || $this->tasks[$i]['level'] != 1) continue;
            
            if ($this->tasks[$i]['duration'] <= $amountToSteal) {
                $amountToSteal -= $this->tasks[$i]['duration'];
                $this->stealFromTask($this->tasks[$i]['id'], $this->tasks[$i]['duration']);
            } else {
                $this->stealFromTask($this->tasks[$i]['id'], $amountToSteal);
                $amountToSteal = 0;
            }
            
            if ($amountToSteal == 0) break;
        }
    }

    private function stealFromTask($taskId, $amount)
    {
        $taskIndex = collect($this->tasks)->search(fn($t) => $t['id'] === $taskId);
        if ($taskIndex === false) return;
        
        $this->tasks[$taskIndex]['duration'] -= $amount;
        if ($this->tasks[$taskIndex]['duration'] <= 0) {
            $this->tasks[$taskIndex]['is_deleted'] = true;
        }
        
        $children = collect($this->tasks)->where('parent_id', $taskId)->keys()->toArray();
        if (!empty($children)) {
            $childAmountToSteal = $amount;
            for ($i = count($children) - 1; $i >= 0; $i--) {
                $childIdx = $children[$i];
                if ($this->tasks[$childIdx]['is_deleted']) continue;
                
                if ($this->tasks[$childIdx]['duration'] <= $childAmountToSteal) {
                    $childAmountToSteal -= $this->tasks[$childIdx]['duration'];
                    $this->stealFromTask($this->tasks[$childIdx]['id'], $this->tasks[$childIdx]['duration']);
                } else {
                    $this->stealFromTask($this->tasks[$childIdx]['id'], $childAmountToSteal);
                    $childAmountToSteal = 0;
                }
                
                if ($childAmountToSteal == 0) break;
            }
        }
    }

    private function addSurplusToNextTask($fromTaskIndex, $amount)
    {
        $level = $this->tasks[$fromTaskIndex]['level'];
        $nextTaskIndex = -1;
        for ($i = $fromTaskIndex + 1; $i < count($this->tasks); $i++) {
            if (!$this->tasks[$i]['is_deleted'] && $this->tasks[$i]['level'] == $level) {
                $nextTaskIndex = $i;
                break;
            }
        }
        
        if ($nextTaskIndex !== -1) {
            $this->addSurplusToTask($this->tasks[$nextTaskIndex]['id'], $amount);
        }
    }
    
    private function addSurplusToTask($taskId, $amount)
    {
        $taskIndex = collect($this->tasks)->search(fn($t) => $t['id'] === $taskId);
        if ($taskIndex === false) return;
        
        $this->tasks[$taskIndex]['duration'] += $amount;
        
        $firstChild = collect($this->tasks)->where('parent_id', $taskId)->where('is_deleted', false)->first();
        if ($firstChild) {
            $this->addSurplusToTask($firstChild['id'], $amount);
        }
    }

    private function getDescendantIds($taskId)
    {
        $ids = [];
        $children = collect($this->tasks)->where('parent_id', $taskId);
        foreach ($children as $child) {
            $ids[] = $child['id'];
            $ids = array_merge($ids, $this->getDescendantIds($child['id']));
        }
        return $ids;
    }

    public function editTask($taskId)
    {
        $this->checkSuperAdmin();
        $task = collect($this->tasks)->firstWhere('id', $taskId);
        if ($task) {
            $this->editingTaskId = $taskId;
            $this->editingName = $task['name'];
            $this->editingNewDuration = $task['duration'];
        }
    }

    public function cancelEdit()
    {
        $this->editingTaskId = null;
        $this->editingName = '';
        $this->editingNewDuration = '';
    }

    public function saveTaskEdit()
    {
        $this->checkSuperAdmin();
        $this->validate([
            'editingName' => 'required|string',
            'editingNewDuration' => 'required|numeric|min:1',
        ]);

        $taskIndex = collect($this->tasks)->search(fn($t) => $t['id'] === $this->editingTaskId);
        if ($taskIndex === false) return;

        $oldDuration = $this->tasks[$taskIndex]['duration'];
        $newDuration = (int) $this->editingNewDuration;
        $difference = $newDuration - $oldDuration;

        $this->tasks[$taskIndex]['name'] = $this->editingName;

        if ($difference > 0) {
            $toSteal = $difference;
            
            if ($this->unassignedDays >= $toSteal) {
                $this->unassignedDays -= $toSteal;
                $toSteal = 0;
            } else {
                $toSteal -= $this->unassignedDays;
                $this->unassignedDays = 0;
            }
            
            if ($toSteal > 0) {
                $this->stealFromBottom($toSteal);
            }
            
            $this->addSurplusToTask($this->editingTaskId, $difference);
            
        } elseif ($difference < 0) {
            $surplus = abs($difference);
            $this->stealFromTask($this->editingTaskId, $surplus);
            $this->addSurplusToNextTask($taskIndex, $surplus);
        }

        $this->cancelEdit();
    }

    public function deleteTask($taskId)
    {
        $this->checkSuperAdmin();
        $taskIndex = collect($this->tasks)->search(fn($t) => $t['id'] === $taskId);
        if ($taskIndex === false) return;
        
        $duration = $this->tasks[$taskIndex]['duration'];
        
        if ($duration > 0) {
            $this->stealFromTask($taskId, $duration);
            
            $level = $this->tasks[$taskIndex]['level'];
            $nextTaskIndex = -1;
            for ($i = $taskIndex + 1; $i < count($this->tasks); $i++) {
                if (!$this->tasks[$i]['is_deleted'] && $this->tasks[$i]['level'] == $level) {
                    $nextTaskIndex = $i;
                    break;
                }
            }
            
            if ($nextTaskIndex !== -1) {
                $this->addSurplusToTask($this->tasks[$nextTaskIndex]['id'], $duration);
            } else {
                $prevTaskIndex = -1;
                for ($i = $taskIndex - 1; $i >= 0; $i--) {
                    if (!$this->tasks[$i]['is_deleted'] && $this->tasks[$i]['level'] == $level) {
                        $prevTaskIndex = $i;
                        break;
                    }
                }
                
                if ($prevTaskIndex !== -1) {
                    $this->addSurplusToTask($this->tasks[$prevTaskIndex]['id'], $duration);
                }
            }
        }

        $idsToRemove = $this->getDescendantIds($taskId);
        $idsToRemove[] = $taskId;
        
        $this->tasks = collect($this->tasks)->reject(fn($t) => in_array($t['id'], $idsToRemove))->values()->toArray();
    }

    public function addTask($taskId)
    {
        $this->checkSuperAdmin();
        $taskIndex = collect($this->tasks)->search(fn($t) => $t['id'] === $taskId);
        if ($taskIndex === false) return;
        
        $newIndex = count($this->tasks) + 1;
        $newTask = [
            'id' => 'temp_' . Str::random(8),
            'parent_id' => $this->tasks[$taskIndex]['parent_id'],
            'level' => $this->tasks[$taskIndex]['level'],
            'name' => 'New Task ' . $newIndex,
            'duration' => 1,
            'unit' => $this->tasks[$taskIndex]['unit'],
            'is_deleted' => false,
            'is_expanded' => false
        ];
        
        array_splice($this->tasks, $taskIndex + 1, 0, [$newTask]);
        
        $toSteal = 1;
        if ($this->unassignedDays >= $toSteal) {
            $this->unassignedDays -= $toSteal;
        } else {
            $toSteal -= $this->unassignedDays;
            $this->unassignedDays = 0;
            $this->stealFromBottom($toSteal);
        }
    }

    public function saveGantt()
    {
        $this->checkSuperAdmin();

        // Persist working-time configuration to the template
        $this->template->update([
            'work_start_time' => $this->divideByHours ? $this->workStartTime . ':00' : null,
            'work_end_time'   => $this->divideByHours ? $this->workEndTime . ':00'   : null,
            'work_days'       => $this->divideByHours ? $this->workDays : null,
            'divide_by_hours' => $this->divideByHours,
        ]);

        // Delete all existing tasks in DB for this template and recreate
        $this->template->tasks()->delete();

        $order = 1;
        $idMap = [];

        foreach ($this->tasks as $task) {
            if ($task['is_deleted'] || $task['duration'] <= 0) {
                continue;
            }

            $parentId = null;
            if ($task['parent_id'] && isset($idMap[$task['parent_id']])) {
                $parentId = $idMap[$task['parent_id']];
            }

            $newTask = TemplateTask::create([
                'project_template_id' => $this->template->id,
                'parent_id'           => $parentId,
                'name'                => $task['name'],
                'duration'            => $task['duration'],
                'unit'                => $task['unit'],
                'order_index'         => $order++,
                'is_expanded'         => $task['is_expanded'] ?? false,
            ]);

            $idMap[$task['id']] = $newTask->id;
        }

        session()->flash('message', 'Gantt chart saved successfully!');
        return redirect()->route('templates.index');
    }

    public function render()
    {
        $visibleTasks = [];
        $visibleColumns = [];

        // 1. Determine visibility
        foreach ($this->tasks as $index => $task) {
            if ($task['is_deleted']) continue;
            
            $visible = true;
            $currId = $task['parent_id'];
            while ($currId) {
                $parent = collect($this->tasks)->firstWhere('id', $currId);
                if (!$parent || !$parent['is_expanded'] || $parent['is_deleted']) {
                    $visible = false;
                    break;
                }
                $currId = $parent['parent_id'];
            }
            
            $this->tasks[$index]['_is_visible'] = $visible;
            if ($visible) {
                $visibleTasks[] = &$this->tasks[$index];
            }
        }

        // 2. Determine columns (leaf nodes in the current visible tree)
        $colIndex = 0;
        foreach ($visibleTasks as &$task) {
            $hasVisibleChild = collect($visibleTasks)->contains('parent_id', $task['id']);
            if ($task['is_expanded'] && $hasVisibleChild) {
                $task['_is_column'] = false;
            } else {
                $task['_is_column'] = true;
                $task['_start_col'] = $colIndex++;
                $task['_span_cols'] = 1;
                $visibleColumns[] = $task;
            }
        }

        // 3. Calculate spans for parent tasks (bottom-up approach)
        for ($i = count($visibleTasks) - 1; $i >= 0; $i--) {
            $task = &$visibleTasks[$i];
            if (!$task['_is_column']) {
                $children = collect($visibleTasks)->where('parent_id', $task['id']);
                if ($children->count() > 0) {
                    $task['_start_col'] = $children->first()['_start_col'];
                    $task['_span_cols'] = $children->sum('_span_cols');
                } else {
                    // Fallback just in case
                    $task['_start_col'] = 0;
                    $task['_span_cols'] = 1;
                }
            }
        }

        return view('livewire.template-gantt-builder', [
            'visibleTasks' => $visibleTasks,
            'visibleColumns' => $visibleColumns,
        ])->layout('layouts.app');
    }
}
