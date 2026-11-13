<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Record an activity performed by an authenticated user.
     */
    public function log(
        string $action,
        string $module,
        ?string $description = null,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $schoolId = null
    ): AuditLog {
        $guard = Auth::guard('super_admin')->check()
            ? 'super_admin'
            : 'web';

        $actor = Auth::guard($guard)->user();

        return AuditLog::create([
            'user_id' => $actor?->id,
            'actor_type' => $guard,
            'school_id' => $schoolId ?? $actor?->school_id,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
