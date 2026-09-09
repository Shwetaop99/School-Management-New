<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Admin\DashboardController;


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
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
    | Two Factor Authentication
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
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        LoginController::class,
        'logout'
    ])->name('logout');


    /*
    |--------------------------------------------------------------------------
    | SCHOOL MANAGEMENT MODULES
    |--------------------------------------------------------------------------
    |
    | These are temporary routes.
    |
    | Later, when your teammates create their controllers,
    | we will replace these closures with their controllers.
    |
    */


    /*
    |----------------------------------------------------------------------
    | Student
    |----------------------------------------------------------------------
    */

    Route::get('/students', function () {
        return 'Student Management';
    })->name('students.index');


    /*
    |----------------------------------------------------------------------
    | Faculty / Teacher
    |----------------------------------------------------------------------
    */

    Route::get('/faculty', function () {
        return 'Faculty Management';
    })->name('faculty.index');


    /*
    |----------------------------------------------------------------------
    | Time Table
    |----------------------------------------------------------------------
    */

    Route::get('/timetable', function () {
        return 'Time Table';
    })->name('timetable.index');


    /*
    |----------------------------------------------------------------------
    | Attendance
    |----------------------------------------------------------------------
    */

    Route::get('/attendance', function () {
        return 'Attendance Management';
    })->name('attendance.index');


    /*
    |----------------------------------------------------------------------
    | Fees
    |----------------------------------------------------------------------
    */

    Route::get('/fees', function () {
        return 'Fees Management';
    })->name('fees.index');


    /*
    |----------------------------------------------------------------------
    | Exam
    |----------------------------------------------------------------------
    */

    Route::get('/exam', function () {
        return 'Exam Management';
    })->name('exam.index');


    /*
    |----------------------------------------------------------------------
    | Results
    |----------------------------------------------------------------------
    */

    Route::get('/results', function () {
        return 'Result Management';
    })->name('results.index');


    /*
    |----------------------------------------------------------------------
    | Notice
    |----------------------------------------------------------------------
    */

    Route::get('/notices', function () {
        return 'Notice Management';
    })->name('notices.index');


    /*
    |----------------------------------------------------------------------
    | Library
    |----------------------------------------------------------------------
    */

    Route::get('/library', function () {
        return 'Library Management';
    })->name('library.index');


    /*
    |----------------------------------------------------------------------
    | Transport
    |----------------------------------------------------------------------
    */

    Route::get('/transport', function () {
        return 'Transport Management';
    })->name('transport.index');


    /*
    |----------------------------------------------------------------------
    | Meal Management
    |----------------------------------------------------------------------
    */

    Route::get('/meals', function () {
        return 'Meal Management';
    })->name('meals.index');


    /*
    |----------------------------------------------------------------------
    | Payroll
    |----------------------------------------------------------------------
    */

    Route::get('/payroll', function () {
        return 'Payroll Management';
    })->name('payroll.index');


    /*
    |----------------------------------------------------------------------
    | Sports
    |----------------------------------------------------------------------
    */

    Route::get('/sports', function () {
        return 'Sports Management';
    })->name('sports.index');


    /*
    |----------------------------------------------------------------------
    | Scholarship
    |----------------------------------------------------------------------
    */

    Route::get('/scholarship', function () {
        return 'Scholarship Management';
    })->name('scholarship.index');


    /*
    |----------------------------------------------------------------------
    | Class
    |----------------------------------------------------------------------
    */

    Route::get('/classes', function () {
        return 'Class Management';
    })->name('classes.index');


    /*
    |----------------------------------------------------------------------
    | Settings
    |----------------------------------------------------------------------
    */

    Route::get('/settings', function () {
        return 'Settings';
    })->name('settings.index');

});