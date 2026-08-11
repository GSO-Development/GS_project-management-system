<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subsidiary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'azure_id',
        'name',
        'logo',
        'description',
        'address',
        'contact_person',
        'contact_email',
        'contact_phone',
        'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
