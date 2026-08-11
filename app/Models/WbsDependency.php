<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbsDependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'predecessor_id',
        'successor_id',
        'dependency_type',
        'lag_days',
    ];

    public function predecessor(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class, 'predecessor_id');
    }

    public function successor(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class, 'successor_id');
    }
}
