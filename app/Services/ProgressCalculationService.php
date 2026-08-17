<?php

namespace App\Services;

use App\Models\Project;
use App\Models\WbsItem;
use Illuminate\Support\Facades\DB;

class ProgressCalculationService
{
    /**
     * Recalculates progress for a WBS item and bubbles up through all ancestor levels to the Project.
     */
    public function updateItemProgress(WbsItem $item, ?string $method = null): void
    {
        if (is_null($method)) {
            $method = \App\Models\SystemSetting::where('key', 'wbs_calculation_method')->value('value') ?? 'weighted';
        }

        DB::transaction(function () use ($item, $method) {
            // Recalculate up to root
            $current = $item->parent_id ? WbsItem::find($item->parent_id) : null;

            while ($current) {
                $children = WbsItem::where('parent_id', $current->id)->get();

                if ($children->count() > 0) {
                    if ($method === 'equal') {
                        $totalProgress = $children->sum('progress');
                        $calculatedProgress = (int) round($totalProgress / $children->count());
                    } else {
                        // Weighted progress calculation
                        $totalWeightedProgress = 0;
                        $totalWeight = 0;

                        foreach ($children as $child) {
                            $weight = (float) ($child->weight ?? 1.0);
                            $totalWeightedProgress += ($child->progress * $weight);
                            $totalWeight += $weight;
                        }

                        $calculatedProgress = $totalWeight > 0
                             ? (int) round($totalWeightedProgress / $totalWeight)
                             : 0;
                    }

                    $current->progress = max(0, min(100, $calculatedProgress));
                    $current->saveQuietly();
                }

                $current = $current->parent_id ? WbsItem::find($current->parent_id) : null;
            }

            // Finally recalculate overall project progress
            $this->updateProjectOverallProgress($item->project_id, $method);
        });
    }

    /**
     * Recalculates overall project progress from top-level WBS items.
     */
    public function updateProjectOverallProgress(int $projectId, ?string $method = null): void
    {
        if (is_null($method)) {
            $method = \App\Models\SystemSetting::where('key', 'wbs_calculation_method')->value('value') ?? 'weighted';
        }

        $project = Project::find($projectId);
        if (!$project) return;

        $topLevelItems = WbsItem::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->get();

        if ($topLevelItems->isEmpty()) {
            $project->overall_progress = 0;
            $project->saveQuietly();
            return;
        }

        if ($method === 'equal') {
            $overall = (int) round($topLevelItems->sum('progress') / $topLevelItems->count());
        } else {
            $totalWeighted = 0;
            $totalWeight = 0;

            foreach ($topLevelItems as $item) {
                $w = (float) ($item->weight ?? 1.0);
                $totalWeighted += ($item->progress * $w);
                $totalWeight += $w;
            }

            $overall = $totalWeight > 0 ? (int) round($totalWeighted / $totalWeight) : 0;
        }

        $project->overall_progress = max(0, min(100, $overall));
        
        // Auto-rollup estimated_hours and actual_hours from WBS items if available
        $wbsEstHours = WbsItem::where('project_id', $projectId)->sum('estimated_hours');
        $wbsActHours = WbsItem::where('project_id', $projectId)->sum('actual_hours');

        if ($wbsEstHours > 0) {
            $project->estimated_hours = $wbsEstHours;
        }
        if ($wbsActHours > 0) {
            $project->actual_hours = $wbsActHours;
        }

        $project->saveQuietly();
    }
}
