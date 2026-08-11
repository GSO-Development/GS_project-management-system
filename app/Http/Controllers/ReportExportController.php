<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    /**
     * Export Projects Summary to PDF using Barryvdh DomPDF.
     */
    public function exportPdf(Request $request)
    {
        $projects = Project::with(['subsidiary', 'projectManager'])->get();

        $pdf = Pdf::loadView('reports.pdf-summary', compact('projects'));
        return $pdf->download('NexusPM_Executive_Report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Projects Summary to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $projects = Project::with(['subsidiary', 'projectManager'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="NexusPM_Projects_' . now()->format('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($projects) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Project Code', 'Project Name', 'Subsidiary', 'Manager', 'Status', 'Health', 'Progress (%)', 'Budget (LKR)', 'Actual Cost (LKR)']);

            foreach ($projects as $p) {
                fputcsv($file, [
                    $p->code,
                    $p->name,
                    $p->subsidiary->name ?? '',
                    $p->projectManager->name ?? '',
                    $p->status->label(),
                    $p->health->label(),
                    $p->overall_progress,
                    $p->estimated_budget,
                    $p->actual_cost,
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }
}
