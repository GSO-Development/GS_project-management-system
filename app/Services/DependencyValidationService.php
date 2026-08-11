<?php

namespace App\Services;

use App\Models\WbsDependency;
use App\Models\WbsItem;
use InvalidArgumentException;

class DependencyValidationService
{
    /**
     * Validates a potential task dependency and throws an exception if invalid.
     */
    public function validateDependency(int $predecessorId, int $successorId): void
    {
        if ($predecessorId === $successorId) {
            throw new InvalidArgumentException("A task cannot depend on itself.");
        }

        $predecessor = WbsItem::find($predecessorId);
        $successor = WbsItem::find($successorId);

        if (!$predecessor || !$successor) {
            throw new InvalidArgumentException("Predecessor or successor task does not exist.");
        }

        if ($predecessor->project_id !== $successor->project_id) {
            throw new InvalidArgumentException("Dependencies cannot cross different projects.");
        }

        // Duplicate dependency check
        $exists = WbsDependency::where('predecessor_id', $predecessorId)
            ->where('successor_id', $successorId)
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException("This dependency already exists.");
        }

        // Circular dependency check (DFS)
        if ($this->hasPath($successorId, $predecessorId)) {
            throw new InvalidArgumentException("Circular dependency detected! Adding this dependency would create a loop.");
        }
    }

    /**
     * Checks if a path exists from startId to targetId through existing dependencies.
     */
    private function hasPath(int $startId, int $targetId, array &$visited = []): bool
    {
        if ($startId === $targetId) {
            return true;
        }

        $visited[$startId] = true;

        $nextDependencies = WbsDependency::where('predecessor_id', $startId)->get();

        foreach ($nextDependencies as $dep) {
            $nextId = $dep->successor_id;
            if (!isset($visited[$nextId])) {
                if ($this->hasPath($nextId, $targetId, $visited)) {
                    return true;
                }
            }
        }

        return false;
    }
}
