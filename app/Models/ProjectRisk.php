<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRisk extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'wbs_item_id',
        'title',
        'description',
        'category',
        'probability',
        'impact',
        'risk_score',
        'owner_id',
        'mitigation_plan',
        'contingency_plan',
        'status',
    ];

    protected $touches = ['project'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function wbsItem(): BelongsTo
    {
        return $this->belongsTo(WbsItem::class, 'wbs_item_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getRiskScoreAttribute($value)
    {
        if ($value && $value > 0) {
            return (int) $value;
        }

        $probMap = ['low' => 1, 'medium' => 2, 'high' => 3];
        $impMap = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];

        $p = $probMap[strtolower($this->probability ?? 'medium')] ?? 2;
        $i = $impMap[strtolower($this->impact ?? 'medium')] ?? 2;

        return $p * $i;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($risk) {
            $probMap = ['low' => 1, 'medium' => 2, 'high' => 3];
            $impMap = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];

            $p = $probMap[strtolower($risk->probability ?? 'medium')] ?? 2;
            $i = $impMap[strtolower($risk->impact ?? 'medium')] ?? 2;

            $risk->risk_score = $p * $i;
        });
    }
}
