<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\StaffCategoryController;
use App\Http\Controllers\Admin\LeaveApplicationController;
use App\Http\Controllers\Admin\ClassTeacherController;


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'login'
    ])->name('login.submit');


    /*
    |--------------------------------------------------------------------------
    | TEACHER ATTENDANCE - TEMPORARY TEST
    |--------------------------------------------------------------------------
    */

    Route::get('/teachers/attendance', [
        TeacherAttendanceController::class,
        'index'
    ])->name('teachers.attendance.index');

    Route::post('/teachers/attendance', [
        TeacherAttendanceController::class,
        'store'
    ])->name('teachers.attendance.store');


    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth')->group(function () {


    Route::get('/teachers/salary/attendance-data', [
    TeacherSalaryController::class,
    'attendanceData'
])->name('teachers.salary.attendance-data');


        /*
        |--------------------------------------------------------------------------
        | TWO FACTOR AUTHENTICATION
        |--------------------------------------------------------------------------
        */

        Route::get('/2fa/setup', [
            TwoFactorController::class,
            'showSetup'
        ])->name('2fa.setup');

        Route::post('/2fa/verify', [
            TwoFactorController::class,
            'verify'
        ])->name('2fa.verify');


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [
            DashboardController::class,
            'index'
        ])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        Route::get('/students', function () {
            return 'Student Management';
        })->name('students.index');


        /*
        |--------------------------------------------------------------------------
        | ASSIGN CLASS TEACHER
        |--------------------------------------------------------------------------
        */

        Route::prefix('teachers/assign-class')
            ->name('teachers.assign-class.')
            ->group(function () {

                Route::get('/', [
                    ClassTeacherController::class,
                    'index'
                ])->name('index');

                Route::get('/create', [
                    ClassTeacherController::class,
                    'create'
                ])->name('create');

                Route::post('/', [
                    ClassTeacherController::class,
                    'store'
                ])->name('store');

                Route::get('/{assignment}/edit', [
                    ClassTeacherController::class,
                    'edit'
                ])->name('edit');

                Route::put('/{assignment}', [
                    ClassTeacherController::class,
                    'update'
                ])->name('update');

                Route::delete('/{assignment}', [
                    ClassTeacherController::class,
                    'destroy'
                ])->name('destroy');
            });


        /*
        |--------------------------------------------------------------------------
        | TEACHER SALARY / PAYROLL
        |--------------------------------------------------------------------------
        */

        Route::prefix('teachers/salary')
            ->name('teachers.salary.')
            ->group(function () {

                Route::get('/', [
                    TeacherSalaryController::class,
                    'index'
                ])->name('index');

                Route::get('/create', [
                    TeacherSalaryController::class,
                    'create'
                ])->name('create');

                /*
                |--------------------------------------------------------------
                | ATTENDANCE DATA
                |--------------------------------------------------------------
                */

                Route::get('/attendance-data', [
                    TeacherSalaryController::class,
                    'attendanceData'
                ])->name('attendance-data');

                Route::post('/', [
                    TeacherSalaryController::class,
                    'store'
                ])->name('store');

                Route::get('/{teacherSalary}', [
                    TeacherSalaryController::class,
                    'show'
                ])->name('show');

                Route::get('/{teacherSalary}/edit', [
                    TeacherSalaryController::class,
                    'edit'
                ])->name('edit');

                Route::put('/{teacherSalary}', [
                    TeacherSalaryController::class,
                    'update'
                ])->name('update');

                Route::delete('/{teacherSalary}', [
                    TeacherSalaryController::class,
                    'destroy'
                ])->name('destroy');
            });


        /*
        |--------------------------------------------------------------------------
        | TEACHER REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get('/teachers/reports', [
            TeacherReportController::class,
            'index'
        ])->name('teachers.reports.index');

        Route::get('/teachers/reports/{teacher}', [
            TeacherReportController::class,
            'show'
        ])->name('teachers.reports.show');

        Route::get('/teachers/reports/{teacher}/pdf', [
            TeacherReportController::class,
            'pdf'
        ])->name('teachers.reports.pdf');

        Route::get('/teachers/reports/{teacher}/excel', [
            TeacherReportController::class,
            'excel'
        ])->name('teachers.reports.excel');


        /*
        |--------------------------------------------------------------------------
        | FACULTY / TEACHER
        |--------------------------------------------------------------------------
        */

        Route::resource('teachers', TeacherController::class);


        /*
        |--------------------------------------------------------------------------
        | TIME TABLE
        |--------------------------------------------------------------------------
        */

        Route::resource('timetable', TimetableController::class)
            ->except(['show']);

        Route::get('/timetable/class', [
            TimetableController::class,
            'classTimetable'
        ])->name('timetable.class');

        Route::get('/timetable/class/pdf', [
            TimetableController::class,
            'classPdf'
        ])->name('timetable.class.pdf');

        Route::get('/timetable/class/excel', [
            TimetableController::class,
            'classExcel'
        ])->name('timetable.class.excel');

        Route::get('/timetable/teacher', [
            TimetableController::class,
            'teacherTimetable'
        ])->name('timetable.teacher');

        Route::get('/timetable/next-time', [
            TimetableController::class,
            'nextTime'
        ])->name('timetable.next-time');


        /*
        |--------------------------------------------------------------------------
        | STAFF CATEGORIES
        |--------------------------------------------------------------------------
        */

        Route::resource('staff-categories', StaffCategoryController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | EXISTING ATTENDANCE
        |--------------------------------------------------------------------------
        */

        Route::get('/attendance', function () {
            return 'Attendance Management';
        })->name('attendance.index');


        /*
        |--------------------------------------------------------------------------
        | FEES
        |--------------------------------------------------------------------------
        */

        Route::get('/fees', function () {
            return 'Fees Management';
        })->name('fees.index');


        /*
        |--------------------------------------------------------------------------
        | EXAM
        |--------------------------------------------------------------------------
        */

        Route::get('/exam', function () {
            return 'Exam Management';
        })->name('exam.index');


        /*
        |--------------------------------------------------------------------------
        | RESULTS
        |--------------------------------------------------------------------------
        */

        Route::get('/results', function () {
            return 'Result Management';
        })->name('results.index');


        /*
        |--------------------------------------------------------------------------
        | NOTICE
        |--------------------------------------------------------------------------
        */

        Route::get('/notices', function () {
            return 'Notice Management';
        })->name('notices.index');


        /*
        |--------------------------------------------------------------------------
        | LIBRARY
        |--------------------------------------------------------------------------
        */

        Route::get('/library', function () {
            return 'Library Management';
        })->name('library.index');


        /*
        |--------------------------------------------------------------------------
        | TRANSPORT
        |--------------------------------------------------------------------------
        */

        Route::get('/transport', function () {
            return 'Transport Management';
        })->name('transport.index');


        /*
        |--------------------------------------------------------------------------
        | MEAL MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/meals', function () {
            return 'Meal Management';
        })->name('meals.index');


        /*
        |--------------------------------------------------------------------------
        | PAYROLL
        |--------------------------------------------------------------------------
        */

        Route::get('/payroll', function () {
            return 'Payroll Management';
        })->name('payroll.index');


        /*
        |--------------------------------------------------------------------------
        | LEAVE MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'leave-applications',
            LeaveApplicationController::class
        );


        /*
        |--------------------------------------------------------------------------
        | SPORTS
        |--------------------------------------------------------------------------
        */

        Route::get('/sports', function () {
            return 'Sports Management';
        })->name('sports.index');


        /*
        |--------------------------------------------------------------------------
        | SCHOLARSHIP
        |--------------------------------------------------------------------------
        */

        Route::get('/scholarship', function () {
            return 'Scholarship Management';
        })->name('scholarship.index');


        /*
        |--------------------------------------------------------------------------
        | CLASS
        |--------------------------------------------------------------------------
        */

        Route::get('/classes', function () {
            return 'Class Management';
        })->name('classes.index');


        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', function () {
            return 'Settings';
        })->name('settings.index');

    });


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        LoginController::class,
        'logout'
    ])->middleware('auth')->name('logout');

});