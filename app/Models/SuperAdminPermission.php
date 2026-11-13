<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuperAdminPermission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'module',
        'description',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            SuperAdminRole::class,
            'super_admin_role_permissions',
            'super_admin_permission_id',
            'super_admin_role_id'
        )->withTimestamps();
    }
}