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

    /**
     * Export Projects Summary to CSV.
     */
    public function exportCsv(Request $request)
    {
        $projects = $this->getScopedProjects($request);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="NexusPM_Executive_Report_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($projects) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Project Code', 'Project Name', 'Subsidiary', 'Project Manager', 'Status', 'Overall Progress (%)', 'Budget (LKR)']);

            foreach ($projects as $p) {
                fputcsv($file, [
                    $p->code,
                    $p->name,
                    $p->subsidiary->name ?? '-',
                    $p->projectManager->name ?? '-',
                    is_object($p->status) && method_exists($p->status, 'label') ? $p->status->label() : (string) $p->status,
                    $p->overall_progress . '%',
                    $p->estimated_budget,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
