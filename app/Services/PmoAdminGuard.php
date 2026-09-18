<?php

namespace App\Services;

use App\Models\User;

/**
 * PmoAdminGuard
 *
 * Enforces the business rule:
 *   "The system must always have at least one active PMO Admin (super_admin role)."
 *
 * Any action that would leave zero PMO admins — whether a delete, role demotion,
 * or batch operation — must be checked here before proceeding.
 */
class PmoAdminGuard
{
    /**
     * Count how many active PMO admin users currently exist.
     */
    public static function getPmoAdminCount(): int
    {
        return User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'pmo_admin']))
            ->where('is_active', true)
            ->count();
    }

    /**
     * Determine whether removing PMO admin status from a given user
     * (via deletion or role demotion) would leave the system with zero PMO admins.
     *
     * Returns true if this user is the LAST active PMO admin.
     */
    public static function isLastAdmin(User $user): bool
    {
        if (!$user->isPmoAdmin()) {
            return false;
        }

        return static::getPmoAdminCount() <= 1;
    }

    /**
     * From an array of user IDs that are about to be batch-deleted or batch-demoted,
     * return the subset that are safe to process.
     * If removing all of them would leave 0 PMO admins, the last one is excluded.
     *
     * @param  array $userIds  Array of integer user IDs
     * @return array ["safe" => [...ids], "blocked" => [...ids]]
     */
    public static function partitionBatch(array $userIds): array
    {
        $adminCount = static::getPmoAdminCount();
        $safe       = [];
        $blocked    = [];

        foreach ($userIds as $id) {
            $user = User::find($id);
            if (!$user) continue;

            if ($user->isPmoAdmin()) {
                if ($adminCount > 1) {
                    $safe[] = $id;
                    $adminCount--;
                } else {
                    $blocked[] = $id;
                }
            } else {
                $safe[] = $id;
            }
        }

        return ["safe" => $safe, "blocked" => $blocked];
    }
}
