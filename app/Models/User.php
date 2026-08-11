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
        $users = static::getUsersForSubsidiary($subsidiaryId);
        $pms = $users->filter(function($u) {
            return $u->hasAnyRole(['super_admin', 'project_manager']) || $u->email === 'superadmin@georgesteuart.com';
        })->values();

        if ($pms->isEmpty()) {
            return static::role(['super_admin', 'project_manager'])->orWhere('email', 'superadmin@georgesteuart.com')->get();
        }

        return $pms;
    }

    public function getRoleNameAttribute(): string
    {
        $role = $this->roles->first()?->name;
        return match($role) {
            'super_admin' => 'Super Admin',
            'project_manager' => 'Project Manager',
            'team_member' => 'Team Member',
            default => 'Team Member',
        };
    }
}
