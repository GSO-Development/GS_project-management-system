<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'user_id',
        'parent_id',
        'content',
    ];

    public static function encodeContentWithVisibility(string $content, ?string $visibility = null): string
    {
        $clean = preg_replace('/^\[VIS:(pmo_pm|team)\]\s*/', '', trim($content));
        if (in_array($visibility, ['pmo_pm', 'team'], true)) {
            return "[VIS:{$visibility}] " . $clean;
        }

        return $clean;
    }

    public function getVisibilityAttribute(): ?string
    {
        $raw = $this->attributes['content'] ?? '';
        if (preg_match('/^\[VIS:(pmo_pm|team)\]/', $raw, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function getContentAttribute(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return preg_replace('/^\[VIS:(pmo_pm|team)\]\s*/', '', $value);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
