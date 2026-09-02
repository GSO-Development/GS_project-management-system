<?php

namespace App\Enums;

enum WbsStatus: string
{
    case BACKLOG = 'backlog';
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case BLOCKED = 'blocked';
    case UNDER_REVIEW = 'under_review';
    case COMPLETED = 'completed';
    case ON_HOLD = 'on_hold';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::BACKLOG => 'Backlog',
            self::NOT_STARTED => 'Not Started',
            self::IN_PROGRESS => 'In Progress',
            self::BLOCKED => 'Blocked',
            self::UNDER_REVIEW => 'Under Review',
            self::COMPLETED => 'Completed',
            self::ON_HOLD => 'On Hold',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::BACKLOG => 'slate',
            self::NOT_STARTED => 'slate',
            self::IN_PROGRESS => 'indigo',
            self::BLOCKED => 'rose',
            self::UNDER_REVIEW => 'cyan',
            self::COMPLETED => 'emerald',
            self::ON_HOLD => 'amber',
            self::CANCELLED => 'red',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::BACKLOG => 'bg-slate-50 text-slate-700 border-slate-200',
            self::NOT_STARTED => 'bg-slate-50 text-slate-700 border-slate-200',
            self::IN_PROGRESS => 'bg-blue-50 text-blue-700 border-blue-200',
            self::BLOCKED => 'bg-rose-50 text-rose-700 border-rose-200',
            self::UNDER_REVIEW => 'bg-purple-50 text-purple-700 border-purple-200',
            self::COMPLETED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::ON_HOLD => 'bg-amber-50 text-amber-800 border-amber-200',
            self::CANCELLED => 'bg-red-50 text-red-700 border-red-200',
        };
    }
}
