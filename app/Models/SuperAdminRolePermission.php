<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuperAdminRolePermission extends Model
{
    protected $table = 'super_admin_role_permissions';

    protected $fillable = [
        'super_admin_role_id',
        'super_admin_permission_id',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            SuperAdminRole::class,
            'super_admin_role_id'
        );
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(
            SuperAdminPermission::class,
            'super_admin_permission_id'
        );
    }
}