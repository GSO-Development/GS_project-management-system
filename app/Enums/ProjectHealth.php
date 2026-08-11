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
            self::DELAYED => 'orange',
            self::CRITICAL => 'rose',
        };
    }
}
