<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Models\ActivityLog;
use App\Models\ProjectStatusUpdate;
use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Enums\WbsStatus;

$sub = Subsidiary::first() ?? Subsidiary::create([
    'code' => 'NEX-01',
    'name' => 'Nexus Global Technologies',
    'status' => 'active',
]);

$pm = User::where('email', 'admin@nexuspm.local')->first() ?? User::first();

$project = Project::create([
    'code' => 'PRJ-NEXUS-888',
    'name' => 'Nexus Global E-Commerce & Retail Platform',
    'description' => 'Comprehensive multi-subsidiary retail platform featuring real-time inventory synchronization, automated WBS milestones, and global payment gateways.',
    'subsidiary_id' => $sub->id,
    'project_manager_id' => $pm->id,
    'created_by' => $pm->id,
    'priority' => Priority::CRITICAL,
    'status' => ProjectStatus::IN_PROGRESS,
    'health' => ProjectHealth::ON_TRACK,
    'overall_progress' => 65,
    'estimated_budget' => 450000,
    'actual_cost' => 185000,
    'start_date' => now()->subMonths(1),
    'deadline' => now()->addMonths(3),
]);

// Attach Members
$allUsers = User::limit(5)->pluck('id')->toArray();
$syncData = [];
foreach ($allUsers as $uId) {
    $syncData[$uId] = ['role' => ($uId == $pm->id) ? 'lead' : 'member'];
}
$project->members()->sync($syncData);

// Seed WBS
$wbs1 = WbsItem::create([
    'project_id' => $project->id,
    'wbs_code' => '1',
    'item_type' => ItemType::PHASE,
    'title' => 'Phase 1: Architecture & Requirements Discovery',
    'status' => WbsStatus::COMPLETED,
    'priority' => Priority::HIGH,
    'progress' => 100,
    'sort_order' => 1,
    'created_by' => $pm->id,
]);

WbsItem::create([
    'project_id' => $project->id,
    'parent_id' => $wbs1->id,
    'wbs_code' => '1.1',
    'item_type' => ItemType::TASK,
    'title' => 'Technical Requirements & ERD Specification',
    'assigned_user_id' => $pm->id,
    'status' => WbsStatus::COMPLETED,
    'priority' => Priority::HIGH,
    'progress' => 100,
    'sort_order' => 1,
    'created_by' => $pm->id,
]);

WbsItem::create([
    'project_id' => $project->id,
    'parent_id' => $wbs1->id,
    'wbs_code' => '1.2',
    'item_type' => ItemType::TASK,
    'title' => 'Database Schema & Security Audit',
    'assigned_user_id' => $pm->id,
    'status' => WbsStatus::COMPLETED,
    'priority' => Priority::HIGH,
    'progress' => 100,
    'sort_order' => 2,
    'created_by' => $pm->id,
]);

$wbs2 = WbsItem::create([
    'project_id' => $project->id,
    'wbs_code' => '2',
    'item_type' => ItemType::PHASE,
    'title' => 'Phase 2: Frontend & Payment Integration',
    'status' => WbsStatus::IN_PROGRESS,
    'priority' => Priority::CRITICAL,
    'progress' => 60,
    'sort_order' => 2,
    'created_by' => $pm->id,
]);

WbsItem::create([
    'project_id' => $project->id,
    'parent_id' => $wbs2->id,
    'wbs_code' => '2.1',
    'item_type' => ItemType::TASK,
    'title' => 'React & Calm Light Mode UI Design System',
    'assigned_user_id' => $pm->id,
    'status' => WbsStatus::COMPLETED,
    'priority' => Priority::HIGH,
    'progress' => 100,
    'sort_order' => 1,
    'created_by' => $pm->id,
]);

WbsItem::create([
    'project_id' => $project->id,
    'parent_id' => $wbs2->id,
    'wbs_code' => '2.2',
    'item_type' => ItemType::TASK,
    'title' => 'Mobile Checkout & Payment Gateway Integration',
    'assigned_user_id' => $pm->id,
    'status' => WbsStatus::IN_PROGRESS,
    'priority' => Priority::CRITICAL,
    'progress' => 50,
    'sort_order' => 2,
    'created_by' => $pm->id,
]);

// Project Status Update
ProjectStatusUpdate::create([
    'project_id' => $project->id,
    'title' => 'Milestone Review - Phase 1 Completed & Phase 2 Active',
    'summary' => 'Requirements discovery and database architecture completed successfully. Payment gateway integration is currently in progress.',
    'reporting_period' => 'Period 4 - 2026 Q3',
    'current_progress' => 65,
    'work_completed' => 'UI Design System, DB Schema, ERD Audit',
    'current_blockers' => 'None. On schedule for target deadline.',
    'next_steps' => 'Complete Payment Gateway Sandbox testing & QA audit.',
    'updated_status' => ProjectStatus::IN_PROGRESS,
    'created_by' => $pm->id,
]);

ActivityLog::create([
    'user_id' => $pm->id,
    'action' => 'created_project',
    'module' => 'projects',
    'record_type' => Project::class,
    'record_id' => $project->id,
    'new_values' => ['name' => $project->name, 'code' => $project->code],
]);

echo "SUCCESS: Project Created with ID {$project->id}\n";
