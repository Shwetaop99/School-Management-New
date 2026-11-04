<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
<<<<<<< HEAD
    use HasFactory, Notifiable;
=======
    use HasFactory, Notifiable, LogsActivity;
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'name',
        'profile_photo',
        'login_id',
        'email',
        'password',
        'status',
        'role_id',
<<<<<<< HEAD
        'super_admin_role_id',
        'school_id',
        'phone',
        'two_factor_secret',
        'two_factor_enabled',
=======
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
    ];

    /**
     * Hidden fields.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    

    /**
     * Casts.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
<<<<<<< HEAD
            'two_factor_enabled' => 'boolean',
            'super_admin_two_factor_enabled' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SCHOOL ADMIN RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * School-level role assigned to the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * School assigned to the user.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Check school-level permission.
     */
    public function can($ability, $arguments = []): bool
=======
        ];
    }

    /**
     * User belongs to one role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check permission helper.
     */
    public function hasPermission($permission)
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
    {
        if (!$this->role) {
            return false;
        }

<<<<<<< HEAD
        return $this->role
            ->permissions()
            ->where('name', $ability)
            ->exists();
    }

    /**
     * Check school-level permission.
     */
    public function hasPermission($permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role
            ->permissions()
=======
        return $this->role->permissions()
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
            ->where('name', $permission)
            ->exists();
    }

    /**
<<<<<<< HEAD
     * Check school-level role.
     */
    public function hasRole($roleName): bool
    {
        return optional($this->role)->name === $roleName;
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Separate platform-level Super Admin role.
     */
    public function superAdminRole(): BelongsTo
    {
        return $this->belongsTo(
            SuperAdminRole::class,
            'super_admin_role_id'
        );
    }

    /**
     * Check platform-level Super Admin permission.
     */
    public function hasSuperAdminPermission(string $permission): bool
    {
        // Primary Super Admin gets unrestricted platform access.
        if (
            $this->hasRole('super_admin') &&
            $this->super_admin_role_id === null
        ) {
            return true;
        }

        // Other platform staff must have an active platform role.
        if (
            !$this->superAdminRole ||
            $this->superAdminRole->status !== 'Active'
        ) {
            return false;
        }

        return $this->superAdminRole
            ->permissions()
            ->where('name', $permission)
            ->exists();
    }
=======
     * Check role helper.
     */
    public function hasRole($roleName)
    {
        return optional($this->role)->name === $roleName;
    }
    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['name', 'login_id', 'email', 'status', 'role_id'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
>>>>>>> 41ec77bbca7910b8e82a9f944ff0fcc4bbed263c
}
}