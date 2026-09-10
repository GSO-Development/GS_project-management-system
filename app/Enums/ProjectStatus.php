<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'draft';
    case NOT_STARTED = 'not_started';
    case PLANNING = 'planning';
    case IN_PROGRESS = 'in_progress';
    case AT_RISK = 'at_risk';
    case ON_HOLD = 'on_hold';
    case UNDER_REVIEW = 'under_review';
    case COMPLETED = 'completed';
    case DELAYED = 'delayed';
    case CANCELLED = 'cancelled';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::NOT_STARTED => 'Not Started',
            self::PLANNING => 'Planning',
            self::IN_PROGRESS => 'In Progress',
            self::AT_RISK => 'At Risk',
            self::ON_HOLD => 'On Hold',
            self::UNDER_REVIEW => 'Under Review',
            self::COMPLETED => 'Completed',
            self::DELAYED => 'Delayed',
            self::CANCELLED => 'Cancelled',
            self::ARCHIVED => 'Archived',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'slate',
            self::NOT_STARTED => 'slate',
            self::PLANNING => 'sky',
            self::IN_PROGRESS => 'blue',
            self::AT_RISK => 'amber',
            self::ON_HOLD => 'amber',
            self::UNDER_REVIEW => 'purple',
            self::COMPLETED => 'emerald',
            self::DELAYED => 'rose',
            self::CANCELLED => 'rose',
            self::ARCHIVED => 'slate',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::COMPLETED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::IN_PROGRESS => 'bg-blue-50 text-blue-700 border-blue-200',
            self::AT_RISK => 'bg-amber-50 text-amber-800 border-amber-200',
            self::DELAYED, self::CANCELLED => 'bg-rose-50 text-rose-700 border-rose-200',
            self::ON_HOLD => 'bg-amber-50 text-amber-800 border-amber-200',
            self::UNDER_REVIEW => 'bg-purple-50 text-purple-700 border-purple-200',
            self::PLANNING => 'bg-sky-50 text-sky-700 border-sky-200',
            self::DRAFT, self::NOT_STARTED, self::ARCHIVED => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    public function badgeDotClass(): string
    {
        return match($this) {
            self::COMPLETED => 'bg-emerald-500',
            self::IN_PROGRESS => 'bg-blue-600',
            self::AT_RISK => 'bg-amber-500',
            self::DELAYED, self::CANCELLED => 'bg-rose-500',
            self::ON_HOLD => 'bg-amber-500',
            self::UNDER_REVIEW => 'bg-purple-500',
            self::PLANNING => 'bg-sky-500',
            self::DRAFT, self::NOT_STARTED, self::ARCHIVED => 'bg-slate-400',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])->toArray();
    }
}
