<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT = 'draft';
    case NOT_STARTED = 'not_started';
    case PLANNING = 'planning';
    case IN_PROGRESS = 'in_progress';
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
            self::NOT_STARTED => 'gray',
            self::PLANNING => 'blue',
            self::IN_PROGRESS => 'indigo',
            self::ON_HOLD => 'amber',
            self::UNDER_REVIEW => 'cyan',
            self::COMPLETED => 'emerald',
            self::DELAYED => 'rose',
            self::CANCELLED => 'red',
            self::ARCHIVED => 'zinc',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])->toArray();
    }
}
