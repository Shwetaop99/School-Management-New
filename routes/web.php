<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TeacherReportController;
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

    // Login page
    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    // Login submit
    Route::post('/login', [
        LoginController::class,
        'login'
    ])->name('login.submit');


    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth')->group(function () {

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
        | SCHOOL MANAGEMENT MODULES
        |--------------------------------------------------------------------------
        */

        // Student
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
        | TEACHER SALARY
        |--------------------------------------------------------------------------
        */

        Route::prefix('teachers/salary')
            ->name('teachers.salary.')
            ->group(function () {

                // Salary list
                Route::get('/', [
                    TeacherSalaryController::class,
                    'index'
                ])->name('index');

                // Generate salary
                Route::get('/create', [
                    TeacherSalaryController::class,
                    'create'
                ])->name('create');

                // Store salary
                Route::post('/', [
                    TeacherSalaryController::class,
                    'store'
                ])->name('store');

                // View salary
                Route::get('/{teacherSalary}', [
                    TeacherSalaryController::class,
                    'show'
                ])->name('show');

                // Edit salary
                Route::get('/{teacherSalary}/edit', [
                    TeacherSalaryController::class,
                    'edit'
                ])->name('edit');

                // Update salary
                Route::put('/{teacherSalary}', [
                    TeacherSalaryController::class,
                    'update'
                ])->name('update');

                // Delete salary
                Route::delete('/{teacherSalary}', [
                    TeacherSalaryController::class,
                    'destroy'
                ])->name('destroy');
            });


            // Teacher Reports
Route::get('/teachers/reports', [TeacherReportController::class, 'index'])
    ->name('teachers.reports.index');

Route::get('/teachers/reports/{teacher}', [TeacherReportController::class, 'show'])
    ->name('teachers.reports.show');

Route::get('/teachers/reports/{teacher}/pdf', [TeacherReportController::class, 'pdf'])
    ->name('teachers.reports.pdf');

Route::get('/teachers/reports/{teacher}/excel', [TeacherReportController::class, 'excel'])
    ->name('teachers.reports.excel');

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

      Route::get('/timetable/class', [TimetableController::class, 'classTimetable'])
    ->name('timetable.class');
    
Route::get('timetable/class/pdf', [TimetableController::class, 'classPdf'])
    ->name('timetable.class.pdf');

Route::get('timetable/class/excel', [TimetableController::class, 'classExcel'])
    ->name('timetable.class.excel');
    
    Route::get(
    'timetable/teacher',
    [TimetableController::class, 'teacherTimetable']
)->name('timetable.teacher');

    Route::get(
    'timetable/next-time',
    [TimetableController::class, 'nextTime']
)->name('timetable.next-time');

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE
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

