<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Enums\ApprovalType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'request_type',
        'requested_by',
        'current_value',
        'requested_value',
        'reason',
        'status',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
        'review_comment',
    ];

    protected function casts(): array
    {
        return [
            'request_type' => ApprovalType::class,
            'status' => ApprovalStatus::class,
            'current_value' => 'array',
            'requested_value' => 'array',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApprovalDocument::class);
    }
}
