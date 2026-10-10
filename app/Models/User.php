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
     * The role assigned to this user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check whether the user has a specific permission.
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
     * Check whether the user has a specific permission.
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
     * Check whether the user has a specific role.
     */
    public function hasRole($roleName): bool
    {
        return optional($this->role)->name === $roleName;
    }

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
        ];
    }
}
