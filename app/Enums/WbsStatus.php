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
}
