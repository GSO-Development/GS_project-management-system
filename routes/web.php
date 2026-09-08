<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportExportController;
use App\Livewire\ApprovalManager;
use App\Livewire\AuditLogViewer;
use App\Livewire\CalendarView;
use App\Livewire\DocumentManager;
use App\Livewire\MyTasks;
use App\Livewire\MyLeadProjects;
use App\Livewire\MyCollaboratorProjects;
use App\Livewire\NotificationManager;
use App\Livewire\PmTeamMembers;
use App\Livewire\ProjectIndex;
use App\Livewire\ProjectWorkspace;
use App\Livewire\ReportViewer;
use App\Livewire\RiskBlockerManager;
use App\Livewire\SettingsManager;
use App\Livewire\SubsidiaryManager;
use App\Livewire\UserManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Force Password Change route for new users
    Route::get('/force-password-change', \App\Livewire\ForcePasswordChange::class)->name('force-password-change');

    // Role-based Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects Directory & Workspace
    Route::get('/projects', ProjectIndex::class)->name('projects.index');
    Route::get('/projects/create', \App\Livewire\ProjectCreate::class)->name('projects.create');

    // User-facing sub-pages: My Lead Projects & My Collaborator Projects
    Route::get('/projects/my-leads', MyLeadProjects::class)->name('projects.my-leads');
    Route::get('/projects/my-collaborations', MyCollaboratorProjects::class)->name('projects.my-collaborations');

    Route::get('/projects/{project}', ProjectWorkspace::class)->name('projects.show');

    // My Tasks (Team Member & PM task workspace)
    Route::get('/my-tasks', MyTasks::class)->name('my-tasks.index');

    // Calendar
    Route::get('/calendar', CalendarView::class)->name('calendar.index');

    // Document Repository, Download & View
    Route::get('/documents', DocumentManager::class)->name('documents.index');
    Route::get('/documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Notifications
    Route::get('/notifications', NotificationManager::class)->name('notifications.index');

    // Approvals (Accessible by all authenticated users)
    Route::get('/approvals', ApprovalManager::class)->name('approvals.index');

    // Risks & Blockers Hub
    Route::get('/risks', RiskBlockerManager::class)->name('risks.index');

    // Daily Status Updates (Accessible by all authenticated users)
    Route::get('/daily-updates', \App\Livewire\DailyStatusUpdates::class)->name('daily-updates.index');

    // Reports (Protected by report.view and report.export permissions)
    Route::get('/reports', ReportViewer::class)->name('reports.index');
    Route::get('/reports/export-pdf', [ReportExportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-csv', [ReportExportController::class, 'exportCsv'])->name('reports.export-csv');

    Route::middleware(['role:super_admin|project_manager'])->group(function () {
        Route::get('/team-members', PmTeamMembers::class)->name('team-members.index');
    });

    // Super Admin / PMO Admin Only Governance Routes
    Route::middleware(['role:super_admin|pmo_admin'])->group(function () {
        // PMO Project Monitoring & Tracking (Redirected to Manage All Projects)
        Route::get('/project-monitor', function (\Illuminate\Http\Request $request) {
            return redirect()->route('projects.index', $request->query());
        })->name('project-monitor.index');

        // Templates (PMO Admin Only)
        Route::get('/templates', \App\Livewire\ManageTemplates::class)->name('templates.index');
        Route::get('/templates/{template}/manage', \App\Livewire\TemplateGanttBuilder::class)->name('templates.manage');

        Route::get('/team-monitor', \App\Livewire\TeamMonitor::class)->name('team-monitor.index');
        Route::get('/subsidiaries', SubsidiaryManager::class)->name('subsidiaries.index');
        Route::get('/users', UserManager::class)->name('users.index');
        Route::get('/roles-permissions', \App\Livewire\RolePermissionManager::class)->name('roles-permissions.index');
        Route::get('/audit-logs', AuditLogViewer::class)->name('audit-logs.index');
        Route::get('/settings', SettingsManager::class)->name('settings.index');
    });

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
