<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $moduleFilter = 'all';
    public string $userFilter = 'all';
    public string $dateFilter = 'all';
    public int $perPage = 15;
    public string $sortOrder = 'desc';

    // Detail Modal State
    public bool $showDetailModal = false;
    public ?ActivityLog $selectedLog = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'moduleFilter' => ['except' => 'all'],
        'userFilter' => ['except' => 'all'],
        'dateFilter' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingModuleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingUserFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->moduleFilter = 'all';
        $this->userFilter = 'all';
        $this->dateFilter = 'all';
        $this->resetPage();
    }

    public function viewDetails(int $logId): void
    {
        $this->selectedLog = ActivityLog::with('user')->find($logId);
        if ($this->selectedLog) {
            $this->showDetailModal = true;
        }
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedLog = null;
    }

    public function exportCsv(): StreamedResponse
    {
        $query = $this->buildQuery();
        $logs = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit-logs-' . now()->format('Y-m-d_His') . '.csv"',
        ];

        return response()->stream(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Email', 'Action', 'Module', 'Record Type', 'Record ID', 'IP Address', 'Timestamp']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->user->name ?? 'System',
                    $log->user->email ?? 'N/A',
                    $log->action,
                    $log->module,
                    $log->record_type ?? 'N/A',
                    $log->record_id ?? 'N/A',
                    $log->ip_address ?? 'N/A',
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    protected function buildQuery()
    {
        $query = ActivityLog::with('user');

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
                  ->orWhereHas('user', function ($u) use ($s) {
                      $u->where('name', 'like', $s)
                        ->orWhere('email', 'like', $s);
                  });
            });
        }

        if ($this->moduleFilter !== 'all') {
            $query->where('module', $this->moduleFilter);
        }

        if ($this->userFilter !== 'all') {
            $query->where('user_id', $this->userFilter);
        }

        if ($this->dateFilter === 'today') {
            $query->whereDate('created_at', now()->today());
        } elseif ($this->dateFilter === '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->dateFilter === '30days') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($this->dateFilter === 'this_month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
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

        // Dropdown data
        $usersList = User::orderBy('name')->get();
        $modulesList = ActivityLog::distinct()->whereNotNull('module')->pluck('module')->sort()->values();

        // Paginated Logs
        $logs = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.audit-log-viewer', compact(
            'logs',
            'totalLogsCount',
            'todayLogsCount',
            'activeActorsCount',
            'securityLogsCount',
            'usersList',
            'modulesList'
        ));
    }
}
