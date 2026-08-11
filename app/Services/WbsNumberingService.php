<?php

namespace App\Services;

use App\Models\WbsItem;
use Illuminate\Support\Facades\DB;

class WbsNumberingService
{
    /**
     * Recalculates WBS codes for an entire project using database transactions and row locking.
     */
    public function recalculateProjectWbsCodes(int $projectId): void
    {
        DB::transaction(function () use ($projectId) {
            // Get top level items (phases or root items)
            $rootItems = WbsItem::where('project_id', $projectId)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $index = 1;
            foreach ($rootItems as $root) {
                $code = (string) $index;
                $root->wbs_code = $code;
                $root->saveQuietly();

                $this->recalculateChildren($root, $code);
                $index++;
            }
        });
    }

    /**
     * Recursive helper to update child WBS codes.
     */
    private function recalculateChildren(WbsItem $parent, string $parentCode): void
    {
        $children = WbsItem::where('parent_id', $parent->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        $index = 1;
        foreach ($children as $child) {
            $code = "{$parentCode}.{$index}";
            $child->wbs_code = $code;
            $child->saveQuietly();

            $this->recalculateChildren($child, $code);
            $index++;
        }
    }
}
