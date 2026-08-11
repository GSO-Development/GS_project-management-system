<?php

namespace App\Livewire;

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
    public $showSetupModal = false;
    public $isConfigured = false;
    
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
        $this->template = $template;
        
        $existingTasks = $this->template->tasks()->orderBy('order_index')->get();
        if ($existingTasks->count() > 0) {
            $this->isConfigured = true;
            $this->setupUnit = $existingTasks->first()->unit;
            
            $levelMap = [];
            foreach ($existingTasks as $task) {
                $level = 1;
                if ($task->parent_id && isset($levelMap[$task->parent_id])) {
                    $level = $levelMap[$task->parent_id] + 1;
                }
                $levelMap[$task->id] = $level;
                
                $this->tasks[] = [
                    'id' => (string) $task->id,
                    'parent_id' => $task->parent_id ? (string) $task->parent_id : null,
                    'name' => $task->name,
                    'duration' => $task->duration,
                    'unit' => $task->unit,
                    'is_deleted' => false,
                    'level' => $level,
                    'is_expanded' => (bool) $task->is_expanded,
                ];
            }
        } else {
            $this->showSetupModal = true;
        }
    }

    public function generateTasks()
    {
        $this->validate([
            'setupDuration' => 'required|integer|min:1|max:100',
            'setupUnit' => 'required|in:days,weeks,months',
        ]);

        $this->tasks = [];
        $totalDays = (int) $this->setupDuration;
        
        $this->generateHierarchicalTasks($totalDays);

        $this->isConfigured = true;
        $this->showSetupModal = false;
    }

    private function generateHierarchicalTasks($totalDays)
    {
        $mainIndex = 1;
        $remainingDays = $totalDays;
        $chunkSize = 1;
        
        if ($this->setupUnit === 'weeks') {
            $chunkSize = 7;
        } elseif ($this->setupUnit === 'months') {
            $chunkSize = 30;
        }

        while ($remainingDays > 0) {
            $duration = min($remainingDays, $chunkSize);
            
            // Level 1 Task
            $mainId = 'temp_' . Str::random(8);
            $this->tasks[] = [
                'id' => $mainId,
                'parent_id' => null,
                'name' => $mainIndex . '.0 Task ' . $mainIndex,
                'duration' => $duration,
                'unit' => $this->setupUnit,
                'is_deleted' => false,
                'level' => 1,
                'is_expanded' => false
            ];

            // Subtasks
            if ($this->setupUnit === 'months' && $this->divideByWeeks) {
                $subWeekRemaining = $duration;
                $weekIndex = 1;
                while ($subWeekRemaining > 0) {
                    $weekDuration = min($subWeekRemaining, 7);
                    $subId = 'temp_' . Str::random(8);
                    $this->tasks[] = [
                        'id' => $subId,
                        'parent_id' => $mainId,
                        'name' => $mainIndex . '.' . $weekIndex . '.0 Week ' . $weekIndex,
                        'duration' => $weekDuration,
                        'unit' => 'weeks',
                        'is_deleted' => false,
                        'level' => 2,
                        'is_expanded' => false
                    ];
                    
                    if ($this->divideByDays) {
                        $this->generateDaySubtasks($subId, $mainIndex . '.' . $weekIndex, $weekDuration, 3);
                    }
                    
                    $subWeekRemaining -= $weekDuration;
                    $weekIndex++;
                }
            } elseif (($this->setupUnit === 'months' || $this->setupUnit === 'weeks') && $this->divideByDays) {
                $this->generateDaySubtasks($mainId, $mainIndex, $duration, 2);
            }

            $remainingDays -= $duration;
            $mainIndex++;
        }
    }

    private function generateDaySubtasks($parentId, $prefix, $days, $level)
    {
        for ($i = 1; $i <= $days; $i++) {
            $this->tasks[] = [
                'id' => 'temp_' . Str::random(8),
                'parent_id' => $parentId,
                'name' => $prefix . '.' . $i . '.0 Day ' . $i,
                'duration' => 1,
                'unit' => 'days',
                'is_deleted' => false,
                'level' => $level,
                'is_expanded' => false
            ];
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
                'parent_id' => $parentId,
                'name' => $task['name'],
                'duration' => $task['duration'],
                'unit' => $task['unit'],
                'order_index' => $order++,
                'is_expanded' => $task['is_expanded'] ?? false,
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
