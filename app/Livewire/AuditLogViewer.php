<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $moduleFilter = 'all';
    public string $actionFilter = 'all';
    public string $userFilter = 'all';
    public string $dateFilter = 'all';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $quickTab = 'all'; // 'all', 'projects', 'governance', 'security', 'tasks'
    public int $perPage = 15;
    public string $sortOrder = 'desc';
    public string $viewMode = 'table'; // 'table' | 'timeline'
    public bool $autoRefresh = true;
    public string $diffViewMode = 'visual'; // 'visual' | 'json'

    // Detail Modal State
    public bool $showDetailModal = false;
    public ?ActivityLog $selectedLog = null;
    public ?array $selectedLogRecord = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'moduleFilter' => ['except' => 'all'],
        'actionFilter' => ['except' => 'all'],
        'userFilter' => ['except' => 'all'],
        'dateFilter' => ['except' => 'all'],
        'quickTab' => ['except' => 'all'],
        'viewMode' => ['except' => 'table'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingModuleFilter(): void { $this->resetPage(); }
    public function updatingActionFilter(): void { $this->resetPage(); }
    public function updatingUserFilter(): void { $this->resetPage(); }
    public function updatingDateFilter(): void { $this->resetPage(); }
    public function updatingStartDate(): void { $this->resetPage(); }
    public function updatingEndDate(): void { $this->resetPage(); }
    public function updatingQuickTab(): void { $this->resetPage(); }
    public function updatingSortOrder(): void { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }
    public function updatingViewMode(): void { $this->resetPage(); }

    public function setQuickTab(string $tab): void
    {
        $this->quickTab = $tab;
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['table', 'timeline']) ? $mode : 'table';
    }

    public function setDiffViewMode(string $mode): void
    {
        $this->diffViewMode = in_array($mode, ['visual', 'json']) ? $mode : 'visual';
    }

    public function toggleSortOrder(): void
    {
        $this->sortOrder = $this->sortOrder === 'desc' ? 'asc' : 'desc';
        $this->resetPage();
    }

    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    public function setCardFilter(string $filterType): void
    {
        if ($filterType === 'today') {
            $this->dateFilter = 'today';
            $this->quickTab = 'all';
        } elseif ($filterType === 'security') {
            $this->quickTab = 'security';
            $this->moduleFilter = 'all';
            $this->dateFilter = 'all';
        } elseif ($filterType === 'all') {
            $this->resetFilters();
        }
        $this->resetPage();
    }

    public function filterByUser($userId): void
    {
        $this->resetFilters();
        $this->userFilter = (string)$userId;
        $this->closeDetailModal();
    }

    public function filterByAction(string $action): void
    {
        $this->resetFilters();
        $this->actionFilter = $action;
        $this->closeDetailModal();
    }

    public function filterByRecord($recordId): void
    {
        $this->resetFilters();
        $this->search = (string)$recordId;
        $this->closeDetailModal();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->moduleFilter = 'all';
        $this->actionFilter = 'all';
        $this->userFilter = 'all';
        $this->dateFilter = 'all';
        $this->startDate = null;
        $this->endDate = null;
        $this->quickTab = 'all';
        $this->resetPage();
    }

    public function viewDetails(int $logId): void
    {
        $this->selectedLog = ActivityLog::with('user')->find($logId);
        if ($this->selectedLog) {
            $this->showDetailModal = true;
            $this->diffViewMode = 'visual';
            $this->selectedLogRecord = $this->resolveSingleRecord($this->selectedLog);
        }
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedLog = null;
        $this->selectedLogRecord = null;
    }

    public function resolveSingleRecord(ActivityLog $log): array
    {
        $map = $this->resolveRecordsMap([$log]);
        return $map[$log->id] ?? [
            'title' => null,
            'code' => null,
            'url' => null,
            'is_deleted' => false,
        ];
    }

    /**
     * Efficiently batch resolve display names, codes, URLs, and deletion status for audit records.
     * Prevents N+1 queries by grouping IDs by model type and checking sibling logs when models are deleted.
     */
    public function resolveRecordsMap($logs): array
    {
        $resolved = [];
        $liveModels = [];
        $recordGroups = [];

        foreach ($logs as $log) {
            if ($log->record_type && $log->record_id) {
                $recordGroups[$log->record_type][$log->record_id] = true;
            }
        }

        // Batch load live existing records from database
        foreach ($recordGroups as $type => $ids) {
            $idList = array_keys($ids);
            if (empty($idList)) continue;

            try {
                if ($type === Project::class) {
                    $liveModels[$type] = Project::whereIn('id', $idList)->get(['id', 'code', 'name'])->keyBy('id');
                } elseif ($type === User::class) {
                    $liveModels[$type] = User::whereIn('id', $idList)->get(['id', 'name', 'email'])->keyBy('id');
                } elseif ($type === Subsidiary::class) {
                    $liveModels[$type] = Subsidiary::whereIn('id', $idList)->get(['id', 'name', 'code'])->keyBy('id');
                } elseif ($type === WbsItem::class) {
                    $liveModels[$type] = WbsItem::whereIn('id', $idList)->get(['id', 'title', 'wbs_code'])->keyBy('id');
                } elseif ($type === Role::class) {
                    $liveModels[$type] = Role::whereIn('id', $idList)->get(['id', 'name'])->keyBy('id');
                }
            } catch (\Throwable $e) {
                // Graceful fallback
            }
        }

        // Detect records deleted from database
        $missingByType = [];
        foreach ($recordGroups as $type => $ids) {
            $idList = array_keys($ids);
            $foundIds = isset($liveModels[$type]) ? $liveModels[$type]->keys()->toArray() : [];
            $missing = array_diff($idList, $foundIds);
            if (!empty($missing)) {
                $missingByType[$type] = $missing;
            }
        }

        // Query historical logs for deleted records to extract recorded names/codes
        $historicalNames = [];
        foreach ($missingByType as $type => $missingIds) {
            $histLogs = ActivityLog::where('record_type', $type)
                ->whereIn('record_id', $missingIds)
                ->where(function ($q) {
                    $q->whereNotNull('new_values')->orWhereNotNull('previous_values');
                })
                ->get(['record_id', 'new_values', 'previous_values']);

            foreach ($histLogs as $hLog) {
                if (isset($historicalNames[$type][$hLog->record_id])) {
                    continue;
                }
                $hn = $hLog->new_values ?? [];
                $hp = $hLog->previous_values ?? [];

                $hTitle = $hn['name'] 
                    ?? $hn['project_name'] 
                    ?? $hn['task_title'] 
                    ?? $hn['source_task'] 
                    ?? $hn['title'] 
                    ?? $hn['role_name'] 
                    ?? $hp['name'] 
                    ?? $hp['project_name'] 
                    ?? null;

                $hCode = $hn['code'] 
                    ?? $hn['project_code'] 
                    ?? $hn['wbs_code'] 
                    ?? $hp['code'] 
                    ?? null;

                if ($hTitle) {
                    $historicalNames[$type][$hLog->record_id] = [
                        'title' => $hTitle,
                        'code'  => $hCode,
                    ];
                }
            }
        }

        // Map resolved data per log entry
        foreach ($logs as $log) {
            $title = null;
            $code = null;
            $url = null;
            $isDeleted = false;

            $new = $log->new_values ?? [];
            $prev = $log->previous_values ?? [];

            // 1. Check current log payload
            $title = $new['name'] 
                ?? $new['project_name'] 
                ?? $new['task_title'] 
                ?? $new['source_task'] 
                ?? $new['title'] 
                ?? $new['role_name'] 
                ?? $new['setting_name']
                ?? $new['key']
                ?? $prev['name'] 
                ?? $prev['project_name'] 
                ?? $prev['task_title'] 
                ?? null;

            $code = $new['code'] 
                ?? $new['project_code'] 
                ?? $new['wbs_code'] 
                ?? $prev['code'] 
                ?? null;

            // Handle System Settings / Settings module specifically
            if (str_contains($log->record_type ?? '', 'SystemSetting') || $log->module === 'settings') {
                if (!$title) {
                    $title = 'Global System Configuration';
                }
            }

            // 2. Check live model or historical fallback
            if ($log->record_type && $log->record_id) {
                if (isset($liveModels[$log->record_type][$log->record_id])) {
                    $model = $liveModels[$log->record_type][$log->record_id];
                    if (!$title) {
                        $title = $model->name ?? $model->title ?? null;
                    }
                    if (!$code) {
                        $code = $model->code ?? $model->wbs_code ?? null;
                    }
                    if ($log->record_type === Project::class) {
                        $url = route('projects.show', $log->record_id);
                    }
                } else {
                    if (!str_contains($log->record_type ?? '', 'SystemSetting')) {
                        $isDeleted = true;
                    }
                    if (!$title && isset($historicalNames[$log->record_type][$log->record_id])) {
                        $title = $historicalNames[$log->record_type][$log->record_id]['title'];
                        if (!$code) {
                            $code = $historicalNames[$log->record_type][$log->record_id]['code'];
                        }
                    }
                }
            }

            if (str_contains($log->action, 'delete') || str_contains($log->action, 'deleted')) {
                $isDeleted = true;
                $url = null;
            }

            $resolved[$log->id] = [
                'title'      => $title,
                'code'       => $code,
                'url'        => $url,
                'is_deleted' => $isDeleted,
            ];
        }

        return $resolved;
    }

    public function exportCsv(): StreamedResponse
    {
        $query = $this->buildQuery();
        $logs = $query->get();
        $recordsMap = $this->resolveRecordsMap($logs);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="audit-logs-' . now()->format('Y-m-d_His') . '.csv"',
        ];

        return response()->stream(function () use ($logs, $recordsMap) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, [
                'Log ID', 
                'User / Actor', 
                'User Email', 
                'Action Performed', 
                'Module', 
                'Record Type', 
                'Record ID', 
                'Target Display Name',
                'Target Code',
                'Record Status',
                'Client IP', 
                'User Agent',
                'Previous Values',
                'New Values',
                'Timestamp'
            ]);

            foreach ($logs as $log) {
                $rec = $recordsMap[$log->id] ?? [];
                fputcsv($handle, [
                    $log->id,
                    $log->user->name ?? 'System Automated',
                    $log->user->email ?? 'N/A',
                    $log->action,
                    $log->module,
                    $log->record_type ? class_basename($log->record_type) : 'N/A',
                    $log->record_id ?? 'N/A',
                    $rec['title'] ?? 'N/A',
                    $rec['code'] ?? 'N/A',
                    (!empty($rec['is_deleted'])) ? 'Deleted' : 'Active',
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                    $log->previous_values ? json_encode($log->previous_values, JSON_UNESCAPED_UNICODE) : '',
                    $log->new_values ? json_encode($log->new_values, JSON_UNESCAPED_UNICODE) : '',
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    protected function buildQuery()
    {
        $query = ActivityLog::with('user');

        // Quick Tab Filter
        if ($this->quickTab === 'projects') {
            $query->whereIn('module', ['projects', 'subsidiaries']);
        } elseif ($this->quickTab === 'governance') {
            $query->where(function ($q) {
                $q->where('action', 'like', '%nudge%')
                  ->orWhere('action', 'like', '%ping%')
                  ->orWhere('action', 'like', '%reassign%')
                  ->orWhere('action', 'like', '%cascade%')
                  ->orWhere('action', 'like', '%approv%')
                  ->orWhere('action', 'like', '%reject%');
            });
        } elseif ($this->quickTab === 'security') {
            $query->whereIn('module', ['roles_permissions', 'users']);
        } elseif ($this->quickTab === 'tasks') {
            $query->whereIn('module', ['wbs', 'wbs_items']);
        }

        // Global Search (including action, module, IP, record_id, user name/email, and JSON values)
        if (!empty($this->search)) {
            $raw = trim($this->search);
            $s = '%' . $raw . '%';
            $sUnderscore = '%' . str_replace(' ', '_', strtolower($raw)) . '%';

            $query->where(function ($q) use ($s, $sUnderscore) {
                $q->where('action', 'like', $s)
                  ->orWhere('action', 'like', $sUnderscore)
                  ->orWhere('module', 'like', $s)
                  ->orWhere('module', 'like', $sUnderscore)
                  ->orWhere('ip_address', 'like', $s)
                  ->orWhere('record_id', 'like', $s)
                  ->orWhere('new_values', 'like', $s)
                  ->orWhere('previous_values', 'like', $s)
                  ->orWhereHas('user', function ($u) use ($s) {
                      $u->where('name', 'like', $s)
                        ->orWhere('email', 'like', $s);
                  });
            });
        }

        // Module Dropdown Filter
        if ($this->moduleFilter !== 'all') {
            $query->where('module', $this->moduleFilter);
        }

        // Action Dropdown Filter
        if ($this->actionFilter !== 'all') {
            $query->where('action', $this->actionFilter);
        }

        // User Dropdown Filter
        if ($this->userFilter !== 'all') {
            $query->where('user_id', $this->userFilter);
        }

        // Date Presets & Custom Range
        if ($this->dateFilter === 'today') {
            $query->whereDate('created_at', now()->today());
        } elseif ($this->dateFilter === '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->dateFilter === '30days') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($this->dateFilter === 'this_month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($this->dateFilter === 'custom') {
            if ($this->startDate) {
                $query->whereDate('created_at', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('created_at', '<=', $this->endDate);
            }
        }

        return $query->orderBy('created_at', $this->sortOrder);
    }

    public function render()
    {
        // Summary Metrics
        $totalLogsCount = ActivityLog::count();
        $todayLogsCount = ActivityLog::whereDate('created_at', now()->today())->count();
        $activeActorsCount = ActivityLog::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $securityLogsCount = ActivityLog::whereIn('module', ['roles_permissions', 'users'])->count();

        // Quick Tab Badges Counts
        $tabCounts = [
            'all'        => $totalLogsCount,
            'projects'   => ActivityLog::whereIn('module', ['projects', 'subsidiaries'])->count(),
            'governance' => ActivityLog::where(function ($q) {
                $q->where('action', 'like', '%nudge%')
                  ->orWhere('action', 'like', '%ping%')
                  ->orWhere('action', 'like', '%reassign%')
                  ->orWhere('action', 'like', '%cascade%')
                  ->orWhere('action', 'like', '%approv%')
                  ->orWhere('action', 'like', '%reject%');
            })->count(),
            'security'   => $securityLogsCount,
            'tasks'      => ActivityLog::whereIn('module', ['wbs', 'wbs_items'])->count(),
        ];

        // Dropdown data
        $usersList = User::orderBy('name')->get();
        $modulesList = ActivityLog::distinct()->whereNotNull('module')->pluck('module')->sort()->values();
        $actionsList = ActivityLog::distinct()->whereNotNull('action')->pluck('action')->sort()->values();

        // Paginated Logs & Batch Resolved Target Records
        $logs = $this->buildQuery()->paginate($this->perPage);
        $resolvedRecords = $this->resolveRecordsMap($logs);

        return view('livewire.audit-log-viewer', compact(
            'logs',
            'resolvedRecords',
            'totalLogsCount',
            'todayLogsCount',
            'activeActorsCount',
            'securityLogsCount',
            'tabCounts',
            'usersList',
            'modulesList',
            'actionsList'
        ));
    }
}
