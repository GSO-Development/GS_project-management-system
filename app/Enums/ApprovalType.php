<?php

namespace App\Enums;

enum ApprovalType: string
{
    case NEW_PROJECT_PLAN = 'new_project_plan';
    case UPDATED_WBS_PLAN = 'updated_wbs_plan';
    case WBS_BASELINE = 'wbs_baseline';
    case DEADLINE_EXTENSION = 'deadline_extension';
    case BUDGET_CHANGE = 'budget_change';
    case SCOPE_CHANGE = 'scope_change';
    case PROJECT_COMPLETION = 'project_completion';
    case PROJECT_CANCELLATION = 'project_cancellation';

    public function label(): string
    {
        return match($this) {
            self::NEW_PROJECT_PLAN => 'New Project Plan',
            self::UPDATED_WBS_PLAN => 'Updated WBS Plan',
            self::WBS_BASELINE => 'WBS Baseline',
            self::DEADLINE_EXTENSION => 'Deadline Extension',
            self::BUDGET_CHANGE => 'Budget Change',
            self::SCOPE_CHANGE => 'Scope Change',
            self::PROJECT_COMPLETION => 'Project Completion',
            self::PROJECT_CANCELLATION => 'Project Cancellation',
        };
    }
}
