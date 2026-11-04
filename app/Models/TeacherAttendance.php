<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendance extends Model
{
    use HasFactory, LogsActivity; 

    protected $fillable = [
        'teacher_id',
        'attendance_date',
        'status',
        'overtime_hours',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'overtime_hours' => 'decimal:2',
    ];

    /**
     * Attendance belongs to a teacher.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }}
