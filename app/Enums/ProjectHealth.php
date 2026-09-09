<?php

namespace App\Enums;

enum ProjectHealth: string
{
    case ON_TRACK = 'on_track';
    case AT_RISK = 'at_risk';
    case DELAYED = 'delayed';
    case CRITICAL = 'critical';

    public function label(): string
    {
        return match($this) {
            self::ON_TRACK => 'On Track',
            self::AT_RISK => 'At Risk',
            self::DELAYED => 'Delayed',
            self::CRITICAL => 'Critical',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ON_TRACK => 'emerald',
            self::AT_RISK => 'amber',
            self::DELAYED => 'rose',
            self::CRITICAL => 'rose',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::ON_TRACK => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::AT_RISK => 'bg-amber-50 text-amber-800 border-amber-200',
            self::DELAYED, self::CRITICAL => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }

    public function badgeDotClass(): string
    {
        return match($this) {
            self::ON_TRACK => 'bg-emerald-500',
            self::AT_RISK => 'bg-amber-500',
            self::DELAYED, self::CRITICAL => 'bg-rose-500',
        };
    }
}
