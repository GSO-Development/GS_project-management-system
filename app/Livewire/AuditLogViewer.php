<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $moduleFilter = 'all';

    public function render()
    {
        $query = ActivityLog::with('user');

        if ($this->search) {
            $query->where('action', 'like', "%{$this->search}%");
        }

        if ($this->moduleFilter !== 'all') {
            $query->where('module', $this->moduleFilter);
        }

        $logs = $query->latest()->paginate(15);

        return view('livewire.audit-log-viewer', compact('logs'));
    }
}
