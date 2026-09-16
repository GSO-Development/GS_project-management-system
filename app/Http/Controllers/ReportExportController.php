<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    private function getScopedProjects(Request $request)
    {
        $user = auth()->user();
        $query = Project::with(['subsidiary', 'projectManager']);

        if (!$user->isPmoAdmin()) {
            $query->where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id));
            });
        }

        if ($request->filled('subsidiary') && $request->subsidiary !== 'all') {
            $query->where('subsidiary_id', $request->subsidiary);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return $query->latest()->get();
    }

    /**
     * Export Projects Summary to PDF using Barryvdh DomPDF.
     */
    public function exportPdf(Request $request)
    {
        $projects = $this->getScopedProjects($request);

        $pdf = Pdf::loadView('reports.pdf-summary', compact('projects'));
        return $pdf->download('NexusPM_Executive_Report_' . now()->format('Y-m-d') . '.pdf');
    }
}
