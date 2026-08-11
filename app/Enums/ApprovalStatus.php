<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REVISION_REQUIRED = 'revision_required';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::REVISION_REQUIRED => 'Revision Required',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'slate',
            self::PENDING => 'amber',
            self::APPROVED => 'emerald',
            self::REJECTED => 'rose',
            self::REVISION_REQUIRED => 'cyan',
            self::CANCELLED => 'red',
        };
    }
}
