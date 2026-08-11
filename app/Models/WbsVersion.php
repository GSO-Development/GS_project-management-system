<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbsVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'version_number',
        'change_summary',
        'created_by',
        'status',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
        'review_comments',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApprovalStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
