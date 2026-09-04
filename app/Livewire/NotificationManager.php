<?php

namespace App\Livewire;

use App\Notifications\ApprovalStatusNotification;
use App\Notifications\DailyUpdateNotification;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryTab = 'all'; // 'all', 'updates', 'approvals', 'task_completed'
    public string $statusFilter = 'all'; // 'all', 'unread', 'read'
    public string $projectFilter = 'all'; // 'all' or project name/id
    public array $selectedIds = [];
    public bool $selectAll = false;

    // Active inspection modal
    public ?string $inspectingNotificationId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedProjectFilter(): void
    {
        $this->resetPage();
    }

    public function setCategoryTab(string $tab): void
    {
        $this->categoryTab = $tab;
        $this->resetPage();
    }

    public function setStatusFilter(string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $user = auth()->user();
            $this->selectedIds = $user->notifications()->pluck('id')->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function clearSelection(): void
    {
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function batchMarkRead(): void
    {
        if (empty($this->selectedIds)) return;

        auth()->user()->notifications()->whereIn('id', $this->selectedIds)->update(['read_at' => now()]);
        $count = count($this->selectedIds);
        $this->clearSelection();
        $this->dispatch('toast', message: "✓ {$count} notifications marked as read.", type: 'success');
    }

    public function batchMarkUnread(): void
    {
        if (empty($this->selectedIds)) return;

        auth()->user()->notifications()->whereIn('id', $this->selectedIds)->update(['read_at' => null]);
        $count = count($this->selectedIds);
        $this->clearSelection();
        $this->dispatch('toast', message: "✓ {$count} notifications marked as unread.", type: 'info');
    }

    public function batchDelete(): void
    {
        if (empty($this->selectedIds)) return;

        auth()->user()->notifications()->whereIn('id', $this->selectedIds)->delete();
        $count = count($this->selectedIds);
        $this->clearSelection();
        $this->dispatch('toast', message: "🗑️ {$count} notifications deleted.", type: 'info');
    }

    public function markAsRead(string $id): void
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('toast', message: 'Notification marked as read.', type: 'success');
        }
    }

    /**
     * Mark notification as read AND redirect to the action URL.
     * This avoids wire:click blocking the anchor's natural href navigation.
     */
    public function markAsReadAndRedirect(string $id, string $url): mixed
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->to($url);
    }

    public function markAsUnread(string $id): void
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->update(['read_at' => null]);
            $this->dispatch('toast', message: 'Notification marked as unread.', type: 'info');
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('toast', message: 'All notifications marked as read.', type: 'success');
    }

    public function deleteNotification(string $id): void
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
            $this->selectedIds = array_diff($this->selectedIds, [$id]);
            $this->dispatch('toast', message: 'Notification deleted.', type: 'info');
        }
    }

    public function deleteAllRead(): void
    {
        auth()->user()->notifications()->whereNotNull('read_at')->delete();
        $this->clearSelection();
        $this->dispatch('toast', message: 'Cleared all read notifications.', type: 'info');
    }

    public function acceptProjectAssignment(string $notificationId, int $projectId)
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        $project = \App\Models\Project::findOrFail($projectId);
        $project->acceptByPm($user);

        $this->dispatch('toast', message: "🎉 Project '{$project->name}' leadership accepted successfully!", type: 'success');
        return redirect()->route('projects.show', $project->id);
    }

    public function seedSampleNotifications(): void
    {
        $user = auth()->user();
        if (!$user) return;

        // 1. Updates Category Sample Notifications
        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\DailyUpdateNotification',
            'data' => [
                'category' => 'updates',
                'title' => 'New Daily Status Update',
                'message' => 'Lakshan Pumuditha submitted a daily progress log for "GS Optimize Portal Modernization".',
                'type' => 'info',
                'url' => route('daily-updates.index'),
            ],
            'read_at' => null,
            'created_at' => now()->subMinutes(15),
        ]);

        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\DailyUpdateNotification',
            'data' => [
                'category' => 'updates',
                'title' => 'Project Timeline Shift',
                'message' => 'Project milestone "Phase 2 Security Audit" was rescheduled to Aug 15, 2026.',
                'type' => 'info',
                'url' => route('projects.index'),
            ],
            'read_at' => now()->subHours(2),
            'created_at' => now()->subHours(3),
        ]);

        // 2. Approvals Category Sample Notifications
        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\ApprovalStatusNotification',
            'data' => [
                'category' => 'approvals',
                'title' => 'Baseline Approval Required',
                'message' => 'New WBS Baseline v2.0 for "GS Health Distribution ERP" is pending your review.',
                'type' => 'approval',
                'url' => route('approvals.index'),
            ],
            'read_at' => null,
            'created_at' => now()->subHours(1),
        ]);

        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\ApprovalStatusNotification',
            'data' => [
                'category' => 'approvals',
                'title' => 'Budget Extension Approved',
                'message' => 'Your budget extension request for "GS Teas Supply Chain Portal" was approved.',
                'type' => 'approval',
                'url' => route('approvals.index'),
            ],
            'read_at' => now()->subDay(),
            'created_at' => now()->subDays(1),
        ]);

        // 3. Task Completed Category Sample Notifications
        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\TaskCompletedNotification',
            'data' => [
                'category' => 'task_completed',
                'title' => 'WBS Task Completed',
                'message' => 'Task "OAuth 2.0 Integration & Azure AD Sync" was marked 100% completed by Nadumi Jayawardhana.',
                'type' => 'success',
                'url' => route('my-tasks.index'),
            ],
            'read_at' => null,
            'created_at' => now()->subMinutes(45),
        ]);

        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\TaskCompletedNotification',
            'data' => [
                'category' => 'task_completed',
                'title' => 'Deliverable Milestone Achieved',
                'message' => 'Milestone "Database Migration & Role Synchronization" was successfully completed.',
                'type' => 'success',
                'url' => route('my-tasks.index'),
            ],
            'read_at' => now()->subHours(5),
            'created_at' => now()->subHours(6),
        ]);

        $this->dispatch('toast', message: 'Sample notifications created for demo.', type: 'info');
    }

    public function render()
    {
        $user = auth()->user();

        // Auto-seed samples if user has 0 notifications
        if ($user && $user->notifications()->count() === 0) {
            $this->seedSampleNotifications();
        }

        $allNotifs = $user ? $user->notifications()->get() : collect();

        // Helper to determine notification category
        $categorize = function($n) {
            $cat = $n->data['category'] ?? null;
            if ($cat === 'updates' || $cat === 'schedule_change') return 'updates';
            if ($cat === 'approvals') return 'approvals';
            if (in_array($cat, ['task_completed', 'task_assigned', 'blocker'])) return 'task_completed';

            $msg = strtolower($n->data['message'] ?? $n->data['title'] ?? '');
            if (str_contains($msg, 'approval') || str_contains($msg, 'baseline') || str_contains($msg, 'request') || str_contains($msg, 'assignment') || str_contains($msg, 'declined') || str_contains($msg, 'accepted') || str_contains($msg, 'designated')) {
                return 'approvals';
            }
            if (str_contains($msg, 'completed') || str_contains($msg, 'done') || str_contains($msg, 'finished') || str_contains($msg, 'milestone') || str_contains($msg, 'assigned') || str_contains($msg, 'blocker')) {
                return 'task_completed';
            }
            return 'updates';
        };

        // Extract available projects list for filtering
        $projectsList = $allNotifs->pluck('data.project_name')->filter()->unique()->values();

        // Counts calculation
        $totalCount        = $allNotifs->count();
        $unreadCount       = $allNotifs->whereNull('read_at')->count();
        $readCount         = $allNotifs->whereNotNull('read_at')->count();

        $updatesCount       = $allNotifs->filter(fn($n) => $categorize($n) === 'updates')->count();
        $approvalsCount     = $allNotifs->filter(fn($n) => $categorize($n) === 'approvals')->count();
        $taskCompletedCount = $allNotifs->filter(fn($n) => $categorize($n) === 'task_completed')->count();

        // Base query
        $query = $user->notifications();

        if ($this->statusFilter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->statusFilter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->latest()->get()->filter(function($n) use ($categorize) {
            if ($this->categoryTab !== 'all' && $categorize($n) !== $this->categoryTab) {
                return false;
            }
            if ($this->projectFilter !== 'all') {
                $proj = $n->data['project_name'] ?? '';
                if ($proj !== $this->projectFilter) return false;
            }
            if (!empty($this->search)) {
                $term = strtolower(trim($this->search));
                $msg = strtolower($n->data['message'] ?? $n->data['title'] ?? '');
                $title = strtolower($n->data['title'] ?? '');
                $proj = strtolower($n->data['project_name'] ?? '');
                return str_contains($msg, $term) || str_contains($title, $term) || str_contains($proj, $term);
            }
            return true;
        });

        // Manual pagination for filtered collection
        $page = $this->getPage();
        $perPage = 10;
        $paginatedNotifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $notifications->forPage($page, $perPage)->values(),
            $notifications->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.notification-manager', [
            'notifications'      => $paginatedNotifications,
            'projectsList'       => $projectsList,
            'totalCount'         => $totalCount,
            'unreadCount'        => $unreadCount,
            'readCount'          => $readCount,
            'updatesCount'       => $updatesCount,
            'approvalsCount'     => $approvalsCount,
            'taskCompletedCount' => $taskCompletedCount,
        ]);
    }
}
