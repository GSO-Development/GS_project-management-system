<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbsBaseline extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'wbs_version_id',
        'baseline_number',
        'original_start_date',
        'original_deadline',
        'baseline_data',
        'created_by',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'original_start_date' => 'date',
            'original_deadline' => 'date',
            'baseline_data' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(WbsVersion::class, 'wbs_version_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
