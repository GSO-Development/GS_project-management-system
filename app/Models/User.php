<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'profile_image',
        'subsidiary_id',
        'is_active',
        'must_change_password',
        'last_login_at',
        'azure_id',
        'azure_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function subsidiary(): BelongsTo
    {
        return $this->belongsTo(Subsidiary::class);
    }

    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function assignedWbsItems(): HasMany
    {
        return $this->hasMany(WbsItem::class, 'assigned_user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public static function getUsersForSubsidiary(?int $subsidiaryId)
    {
        if (!$subsidiaryId) {
            return static::where('is_active', true)->get();
        }

        $sub = Subsidiary::find($subsidiaryId);
        $subCode = $sub ? strtolower($sub->code) : '';

        $users = static::where('is_active', true)
            ->where(function($q) use ($subsidiaryId, $subCode) {
                $q->where('subsidiary_id', $subsidiaryId);
                if (!empty($subCode)) {
                    $q->orWhere('email', 'like', "%{$subCode}%");
                }
            })
            ->get();

        if ($users->isEmpty()) {
            return static::where('is_active', true)->get();
        }

        return $users;
    }

    public static function getPmsForSubsidiary(?int $subsidiaryId)
    {
        return static::getUsersForSubsidiary($subsidiaryId);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin') 
            || $this->email === 'admin@nexuspm.local' 
            || $this->email === 'superadmin@georgesteuart.com' 
            || $this->id === 1;
    }

    public function getRoleNameAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'PMO Admin';
        }
        if ($this->hasRole('project_manager')) {
            return 'Project Manager';
        }
        if ($this->hasRole('team_member')) {
            return 'Team Member';
        }
        return 'Regular User';
    }
}

