<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NoticeController;


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
    | TWO FACTOR AUTHENTICATION
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Notice Management
    Route::get('/notices', [NoticeController::class, 'index'])
        ->name('notices.index');

    Route::get('/notices/create', [NoticeController::class, 'create'])
        ->name('notices.create');

    Route::post('/notices', [NoticeController::class, 'store'])
    ->name('notices.store');

    Route::get('/notices/{notice}', [NoticeController::class, 'show'])
    ->name('notices.show');

    Route::get('/notices/create', [NoticeController::class, 'create'])
    ->name('notices.create');

    Route::get('/notices/{notice}/edit', [NoticeController::class, 'edit'])
    ->name('notices.edit');

    Route::put('/notices/{notice}', [NoticeController::class, 'update'])
    ->name('notices.update');

    Route::delete('/notices/{notice}', [NoticeController::class, 'destroy'])
    ->name('notices.destroy');



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

        Route::get('/attendance/student', function () {
    return view('admin.attendance.student');
})->name('attendance.student');


        // Faculty / Teacher
        Route::get('/faculty', function () {
            return 'Faculty Management';
        })->name('faculty.index');


        // Time Table
        Route::get('/timetable', function () {
            return 'Time Table';
        })->name('timetable.index');


        // Attendance
        Route::get('/attendance', function () {
            return 'Attendance Management';
        })->name('attendance.index');


        // Fees
        Route::get('/fees', function () {
            return 'Fees Management';
        })->name('fees.index');


        // Exam
        Route::get('/exam', function () {
            return 'Exam Management';
        })->name('exam.index');


        // Results
        Route::get('/results', function () {
            return 'Result Management';
        })->name('results.index');


        // Notice
        Route::get('/notices', [NoticeController::class, 'index'])
    ->name('notices.index');


        // Library
        Route::get('/library', function () {
            return 'Library Management';
        })->name('library.index');


        // Transport
        Route::get('/transport', function () {
            return 'Transport Management';
        })->name('transport.index');


        // Meal Management
        Route::get('/meals', function () {
            return 'Meal Management';
        })->name('meals.index');


        // Payroll
        Route::get('/payroll', function () {
            return 'Payroll Management';
        })->name('payroll.index');


        // Sports
        Route::get('/sports', function () {
            return 'Sports Management';
        })->name('sports.index');


        // Scholarship
        Route::get('/scholarship', function () {
            return 'Scholarship Management';
        })->name('scholarship.index');


        // Class
        Route::get('/classes', function () {
            return 'Class Management';
        })->name('classes.index');


        // Settings
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