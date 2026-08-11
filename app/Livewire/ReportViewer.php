<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Subsidiary;
use Livewire\Component;

class ReportViewer extends Component
{
    public string $subsidiaryFilter = 'all';
    public string $statusFilter = 'all';

    public function render()
    {
        $query = Project::with(['subsidiary', 'projectManager']);

        if ($this->subsidiaryFilter !== 'all') {
            $query->where('subsidiary_id', $this->subsidiaryFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->get();
        $subsidiaries = Subsidiary::all();

        $totalBudget = $projects->sum('estimated_budget');
        $totalCost = $projects->sum('actual_cost');
        $avgProgress = $projects->count() > 0 ? round($projects->avg('overall_progress')) : 0;

        return view('livewire.report-viewer', compact('projects', 'subsidiaries', 'totalBudget', 'totalCost', 'avgProgress'));
    }
}
