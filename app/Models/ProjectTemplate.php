<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    protected $fillable = ['name', 'description'];

    public function tasks()
    {
        return $this->hasMany(TemplateTask::class)->orderBy('order_index');
    }
}
