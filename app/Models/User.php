<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'super_admin_role_id',
        'school_id',
        'phone',
        'two_factor_secret',
        'two_factor_enabled',
    ];

    /**
     * Hidden fields.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * Attribute casts.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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
    {
        if (!$this->role) {
            return false;
        }

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
            ->where('name', $permission)
            ->exists();
    }

    /**
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
}
