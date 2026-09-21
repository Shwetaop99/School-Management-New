<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('name', $permission)
            ->exists();
    }

    /**
     * Check role helper.
     */
    public function hasRole($roleName)
    {
        return optional($this->role)->name === $roleName;
    }
}