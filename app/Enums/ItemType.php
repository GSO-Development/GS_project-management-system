<?php

namespace App\Enums;

enum ItemType: string
{
    case PHASE = 'phase';
    case WORK_PACKAGE = 'work_package';
    case TASK = 'task';
    case SUBTASK = 'subtask';
    case MILESTONE = 'milestone';

    public function label(): string
    {
        return match($this) {
            self::PHASE => 'Phase',
            self::WORK_PACKAGE => 'Work Package',
            self::TASK => 'Task',
            self::SUBTASK => 'Subtask',
            self::MILESTONE => 'Milestone',
        };
    }
}
