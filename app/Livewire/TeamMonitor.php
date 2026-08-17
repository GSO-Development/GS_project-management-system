<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;

class TeamMonitor extends Component
{
    public string $search = '';
    public string $roleFilter = 'all';
    public string $issueFilter = 'all'; // 'all', 'blocked', 'overdue'

    public function updatedSearch() {}
    public function updatedRoleFilter() {}
    public function updatedIssueFilter() {}

    public function render()
    {
        $user = auth()->user();
        $today = now()->today()->toDateString();

        // 1. Determine staff users allowed under current role filter
        $pmIds = Project::whereNotNull('project_manager_id')->pluck('project_manager_id')->unique()->toArray();
        $superAdminIds = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->pluck('id')->toArray();

        $usersQuery = User::where('is_active', true);
        if ($this->roleFilter === 'pm') {
            $usersQuery->whereIn('id', $pmIds);
        } elseif ($this->roleFilter === 'member') {
            $usersQuery->whereNotIn('id', array_merge($pmIds, $superAdminIds));
        } else {
            // "all" staff includes PMs, Team Members, and Super Admins
            $usersQuery->where(function($q) use ($pmIds, $superAdminIds) {
                $q->whereIn('id', $pmIds)
                  ->orWhereIn('id', $superAdminIds)
                  ->orWhereHas('projects')
                  ->orWhereHas('assignedWbsItems');
            });
        }

        $allUsersList = $usersQuery->get();
        $allowedUserIds = $allUsersList->pluck('id')->toArray();

        // 2. Logged Issues & Delays query with strict Role & Issue & Search Filters
        $issuesQuery = WbsItem::whereHas('project')
            ->whereNotNull('delay_reason')
            ->whereIn('assigned_user_id', $allowedUserIds)
            ->with(['assignedUser', 'project', 'delayReporter']);

        if ($this->search) {
            $issuesQuery->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('delay_reason', 'like', "%{$this->search}%")
                  ->orWhereHas('assignedUser', fn($uq) => $uq->where('name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->issueFilter === 'blocked') {
            $issuesQuery->where('status', 'blocked');
        } elseif ($this->issueFilter === 'overdue') {
            $issuesQuery->where('end_date', '<', now()->today())
                        ->whereNotIn('status', ['completed', 'cancelled']);
        }

        $loggedIssues = $issuesQuery->orderBy('updated_at', 'desc')->get();

        // 3. Team Daily Execution Stats filtered strictly by allowedUserIds
        $teamDailyStatsRaw = WbsItem::whereHas('project')
            ->whereIn('assigned_user_id', $allowedUserIds)
            ->with(['assignedUser', 'project'])
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->groupBy('assigned_user_id')
            ->map(function($items) use ($today) {
                $user = $items->first()->assignedUser;
                if (!$user) return null;

                $total      = $items->count();
                $done       = $items->where('status', 'completed')->count();
                $inProg     = $items->where('status', 'in_progress')->count();
                $blocked    = $items->where('status', 'blocked')->count();
                $notStarted = $items->whereIn('status', ['not_started', 'backlog'])->count();
                $overdue    = $items->filter(fn($t) => $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status->value, ['completed','cancelled']))->count();

                $issues = $items->filter(fn($t) => !empty($t->delay_reason));

                // Active / Attention-Required tasks ONLY
                $activeTasks = $items->filter(function($t) {
                    $isOverdue = $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status->value, ['completed', 'cancelled']);
                    $hasReason = !empty($t->delay_reason);
                    $isActiveStatus = in_array($t->status->value, ['in_progress', 'blocked', 'on_hold']);
                    return $isOverdue || $hasReason || $isActiveStatus;
                })->values();

                return [
                    'user'        => $user,
                    'total'       => $total,
                    'done'        => $done,
                    'in_progress' => $inProg,
                    'blocked'     => $blocked,
                    'not_started' => $notStarted,
                    'overdue'     => $overdue,
                    'pct'         => $total > 0 ? round(($done / $total) * 100) : 0,
                    'tasks'       => $activeTasks->take(4),
                    'issues'      => $issues,
                ];
            })
            ->filter();

        // Ensure all users matching the role filter are included in stats map
        $statsCollection = collect();
        foreach ($allUsersList as $u) {
            if ($teamDailyStatsRaw->has($u->id)) {
                $statsCollection->put($u->id, $teamDailyStatsRaw->get($u->id));
            } else {
                $statsCollection->put($u->id, [
                    'user'        => $u,
                    'total'       => 0,
                    'done'        => 0,
                    'in_progress' => 0,
                    'blocked'     => 0,
                    'not_started' => 0,
                    'overdue'     => 0,
                    'pct'         => 0,
                    'tasks'       => collect(),
                    'issues'      => collect(),
                ]);
            }
        }

        // Apply Issue Filter to staff cards if issueFilter is active
        if ($this->issueFilter === 'blocked') {
            $statsCollection = $statsCollection->filter(fn($s) => $s['blocked'] > 0);
        } elseif ($this->issueFilter === 'overdue') {
            $statsCollection = $statsCollection->filter(fn($s) => $s['overdue'] > 0);
        }

        // Apply Search filter to staff cards if search input is active
        if (!empty(trim($this->search))) {
            $searchStr = strtolower(trim($this->search));
            $statsCollection = $statsCollection->filter(function($stat) use ($searchStr) {
                $userName = strtolower($stat['user']->name ?? '');
                $userEmail = strtolower($stat['user']->email ?? '');
                $matchesUser = str_contains($userName, $searchStr) || str_contains($userEmail, $searchStr);
                $matchesTask = $stat['tasks']->contains(fn($t) => str_contains(strtolower($t->title), $searchStr) || str_contains(strtolower($t->delay_reason ?? ''), $searchStr));
                return $matchesUser || $matchesTask;
            });
        }

        // Sort staff by attention required (blocked count * 3 + overdue * 2 + issues count)
        $teamDailyStats = $statsCollection->sortByDesc(fn($stat) => $stat['blocked'] * 3 + $stat['overdue'] * 2 + $stat['issues']->count());

        $totalIssuesCount  = WbsItem::whereHas('project')->whereNotNull('delay_reason')->count();
        $totalBlockedCount = WbsItem::whereHas('project')->where('status', 'blocked')->count();
        $totalOverdueCount = WbsItem::whereHas('project')->where('end_date', '<', now()->today())->whereNotIn('status', ['completed', 'cancelled'])->count();
        $totalPmCount      = User::where('is_active', true)->whereIn('id', $pmIds)->count();
        $totalMemberCount  = User::where('is_active', true)->whereNotIn('id', array_merge($pmIds, $superAdminIds))->count();

        return view('livewire.team-monitor', compact(
            'loggedIssues',
            'teamDailyStats',
            'totalIssuesCount',
            'totalBlockedCount',
            'totalOverdueCount',
            'totalPmCount',
            'totalMemberCount'
        ))->layout('layouts.app');
    }
}
