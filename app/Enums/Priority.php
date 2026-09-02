<?php

namespace App\Enums;

enum Priority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function label(): string
    {
        return match($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::CRITICAL => 'Critical',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LOW => 'slate',
            self::MEDIUM => 'amber',
            self::HIGH => 'orange',
            self::CRITICAL => 'rose',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::LOW => 'bg-slate-100 text-slate-700 border border-slate-200',
            self::MEDIUM => 'bg-blue-100 text-blue-800 border border-blue-200',
            self::HIGH => 'bg-amber-100 text-amber-800 border border-amber-200',
            self::CRITICAL => 'bg-rose-100 text-rose-800 border border-rose-200',
        };
    }
}
