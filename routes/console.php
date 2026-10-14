<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



use App\Http\Controllers\Admin\TeacherAttendanceController;

Route::get('teachers/attendance', [TeacherAttendanceController::class, 'index'])
    ->name('teachers.attendance.index');

Route::post('teachers/attendance', [TeacherAttendanceController::class, 'store'])
    ->name('teachers.attendance.store');