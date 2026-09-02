<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'work_start_time',
        'work_end_time',
        'work_days',
        'divide_by_hours',
    ];

    protected function casts(): array
    {
        return [
            'work_days'      => 'array',
            'divide_by_hours' => 'boolean',
        ];
    }

    public function tasks()
    {
        return $this->hasMany(TemplateTask::class)->orderBy('order_index');
    }
}
