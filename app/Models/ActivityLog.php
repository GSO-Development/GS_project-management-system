<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    public const ACTOR_USER        = 'user';
    public const ACTOR_SYSTEM      = 'system';
    public const ACTOR_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'user_id',
        'action',
        'actor_type',
        'module',
        'record_type',
        'record_id',
        'batch_uuid',
        'previous_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'previous_values' => 'array',
            'new_values'      => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUserActions(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_USER);
    }

    public function scopeSystemActions(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_SYSTEM);
    }

    public function scopeMaintenanceActions(Builder $query): Builder
    {
        return $query->where('actor_type', self::ACTOR_MAINTENANCE);
    }

    public function scopeByBatch(Builder $query, string $batchUuid): Builder
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    public function isUserAction(): bool
    {
        return ($this->actor_type ?? self::ACTOR_USER) === self::ACTOR_USER;
    }

    public function isSystemAction(): bool
    {
        return $this->actor_type === self::ACTOR_SYSTEM;
    }

    public function isMaintenanceAction(): bool
    {
        return $this->actor_type === self::ACTOR_MAINTENANCE;
    }
}
