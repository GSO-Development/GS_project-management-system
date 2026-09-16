<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Subsidiary;
use Livewire\Component;

class ReportViewer extends Component
{
    public string $subsidiaryFilter = 'all';
    public string $statusFilter = 'all';

    public function mount(): void
    {
        // Reports page is accessible by all authenticated users, scoped to their projects
    }

    public function clearFilters(): void
    {
        $this->subsidiaryFilter = 'all';
        $this->statusFilter = 'all';
    }

    public function render()
    {
        $user = auth()->user();
        $query = Project::with(['subsidiary', 'projectManager']);

        // Scope strictly to projects where this user is the designated Project Manager if not PMO Admin
        if (!$user->isPmoAdmin()) {
            $query->where('project_manager_id', $user->id);
        }

        if ($this->subsidiaryFilter !== 'all') {
            $query->where('subsidiary_id', $this->subsidiaryFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->latest()->get();
        $subsidiaries = Subsidiary::orderBy('name')->get();

        $totalBudget = $projects->sum('estimated_budget');
        $totalCost = $projects->sum('actual_cost');
        $avgProgress = $projects->count() > 0 ? round($projects->avg('overall_progress')) : 0;

        return view('livewire.report-viewer', compact('projects', 'subsidiaries', 'totalBudget', 'totalCost', 'avgProgress'));
    }
}
