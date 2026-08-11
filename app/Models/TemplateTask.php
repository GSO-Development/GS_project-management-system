<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateTask extends Model
{
    protected $fillable = ['project_template_id', 'parent_id', 'name', 'duration', 'unit', 'order_index', 'is_expanded'];

    public function template()
    {
        return $this->belongsTo(ProjectTemplate::class, 'project_template_id');
    }

    public function parent()
    {
        return $this->belongsTo(TemplateTask::class, 'parent_id');
    }

    public function subtasks()
    {
        return $this->hasMany(TemplateTask::class, 'parent_id')->orderBy('order_index');
    }
}
