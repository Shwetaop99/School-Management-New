<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\StaffCategoryController;
use App\Http\Controllers\Admin\LeaveApplicationController;
use App\Http\Controllers\Admin\ClassTeacherController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookIssueController;
use App\Http\Controllers\Admin\OtherStaffController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\LibraryReportController;
use App\Http\Controllers\Admin\LibraryReportDownloadController;

/*
|--------------------------------------------------------------------------
| Class Management Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Class\SchoolClassController;
use App\Http\Controllers\Class\SubjectController;


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ADMIN LOGIN
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
        | TEACHER ATTENDANCE
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

            /*
            |--------------------------------------------------------------------------
            | TEACHER SALARY
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | These routes are placed BEFORE the Teacher resource routes
            | so /admin/teachers/salary is not treated as /admin/teachers/{teacher}.
            |
            */

            Route::prefix('teachers/salary')
                ->name('teachers.salary.')
                ->group(function () {

                    // Salary List
                    Route::get('/', [
                        TeacherSalaryController::class,
                        'index'
                    ])->name('index');

                    // Create Salary
                    Route::get('/create', [
                        TeacherSalaryController::class,
                        'create'
                    ])->name('create');

                    // Store Salary
                    Route::post('/', [
                        TeacherSalaryController::class,
                        'store'
                    ])->name('store');

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    | attendance-data must come BEFORE /{salary}
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/attendance-data', [
                        TeacherSalaryController::class,
                        'attendanceData'
                    ])->name('attendance-data');

                    // View Salary
                    Route::get('/{salary}', [
                        TeacherSalaryController::class,
                        'show'
                    ])->name('show');

                    // Edit Salary
                    Route::get('/{salary}/edit', [
                        TeacherSalaryController::class,
                        'edit'
                    ])->name('edit');

                    // Update Salary
                    Route::put('/{salary}', [
                        TeacherSalaryController::class,
                        'update'
                    ])->name('update');

                    // Delete Salary
                    Route::delete('/{salary}', [
                        TeacherSalaryController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | TEACHERS
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'teachers',
                TeacherController::class
            );


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

                    Route::get('/{classTeacher}/edit', [
                        ClassTeacherController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{classTeacher}', [
                        ClassTeacherController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{classTeacher}', [
                        ClassTeacherController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | TIMETABLE MANAGEMENT
            |--------------------------------------------------------------------------
            */

            // Timetable List
            Route::get('/timetable', [
                TimetableController::class,
                'index'
            ])->name('timetable.index');

            // Create Timetable
            Route::get('/timetable/create', [
                TimetableController::class,
                'create'
            ])->name('timetable.create');

            // Store Timetable
            Route::post('/timetable', [
                TimetableController::class,
                'store'
            ])->name('timetable.save');

            // Teacher Timetable
            Route::get('/timetable/teacher', [
                TimetableController::class,
                'teacherTimetable'
            ])->name('timetable.teacher');

            // Class Timetable
            Route::get('/timetable/class', [
                TimetableController::class,
                'classTimetable'
            ])->name('timetable.class');

            // Class Timetable PDF
            Route::get('/timetable/class/pdf', [
                TimetableController::class,
                'classPdf'
            ])->name('timetable.class.pdf');

            // Class Timetable Excel
            Route::get('/timetable/class/excel', [
                TimetableController::class,
                'classExcel'
            ])->name('timetable.class.excel');

            // View Timetable
            Route::get('/timetable/{timetable}', [
                TimetableController::class,
                'show'
            ])->name('timetable.show');

            // Edit Timetable
            Route::get('/timetable/{timetable}/edit', [
                TimetableController::class,
                'edit'
            ])->name('timetable.edit');

            // Update Timetable
            Route::put('/timetable/{timetable}', [
                TimetableController::class,
                'update'
            ])->name('timetable.update');

            // Delete Timetable
            Route::delete('/timetable/{timetable}', [
                TimetableController::class,
                'destroy'
            ])->name('timetable.destroy');


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
            | LEAVE APPLICATIONS
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'leave-applications',
                LeaveApplicationController::class
            );


            /*
            |--------------------------------------------------------------------------
            | NOTICE MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/notices', [
                NoticeController::class,
                'index'
            ])->name('notices.index');

            Route::get('/notices/create', [
                NoticeController::class,
                'create'
            ])->name('notices.create');

            Route::post('/notices', [
                NoticeController::class,
                'store'
            ])->name('notices.store');

            Route::get('/notices/{notice}/edit', [
                NoticeController::class,
                'edit'
            ])->name('notices.edit');

            Route::put('/notices/{notice}', [
                NoticeController::class,
                'update'
            ])->name('notices.update');

            Route::delete('/notices/{notice}', [
                NoticeController::class,
                'destroy'
            ])->name('notices.destroy');

            Route::get('/notices/{notice}', [
                NoticeController::class,
                'show'
            ])->name('notices.show');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - BOOKS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/books', [
                BookController::class,
                'index'
            ])->name('library.books.index');

            Route::get('/library/books/create', [
                BookController::class,
                'create'
            ])->name('library.books.create');

            Route::post('/library/books', [
                BookController::class,
                'store'
            ])->name('library.books.store');

            Route::get('/library/books/{book}/edit', [
                BookController::class,
                'edit'
            ])->name('library.books.edit');

            Route::put('/library/books/{book}', [
                BookController::class,
                'update'
            ])->name('library.books.update');

            Route::delete('/library/books/{book}', [
                BookController::class,
                'destroy'
            ])->name('library.books.destroy');

            Route::get('/library/books/{book}', [
                BookController::class,
                'show'
            ])->name('library.books.show');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - ISSUE BOOK
            |--------------------------------------------------------------------------
            */

            Route::get('/library/issues', [
                BookIssueController::class,
                'index'
            ])->name('library.issues.index');

            Route::get('/library/issues/create', [
                BookIssueController::class,
                'create'
            ])->name('library.issues.create');

            Route::post('/library/issues', [
                BookIssueController::class,
                'store'
            ])->name('library.issues.store');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - RETURNS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/returns', [
                BookIssueController::class,
                'returns'
            ])->name('library.returns.index');

            Route::post('/library/issues/{issue}/return', [
                BookIssueController::class,
                'returnBook'
            ])->name('library.issues.return');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - FINES
            |--------------------------------------------------------------------------
            */

            Route::get('/library/fines', [
                BookIssueController::class,
                'fines'
            ])->name('library.fines.index');

            Route::patch('/library/fines/{issue}/status', [
                BookIssueController::class,
                'updateFineStatus'
            ])->name('library.fines.status');


            /*
            |--------------------------------------------------------------------------
            | LIBRARIAN
            |--------------------------------------------------------------------------
            */

            Route::get('/library/librarian', [
                LibrarianController::class,
                'index'
            ])->name('library.librarian.index');

            Route::get('/library/librarian/{librarian}', [
                LibrarianController::class,
                'show'
            ])->name('library.librarian.show');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/reports', [
                LibraryReportController::class,
                'index'
            ])->name('library.reports.index');

            Route::get('/library/reports/pdf', [
                LibraryReportDownloadController::class,
                'pdf'
            ])->name('library.reports.pdf');

            Route::get('/library/reports/excel', [
                LibraryReportDownloadController::class,
                'excel'
            ])->name('library.reports.excel');


            /*
            |--------------------------------------------------------------------------
            | OTHER STAFF
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'other-staff',
                OtherStaffController::class
            )->names([
                'index'   => 'other-staff.index',
                'create'  => 'other-staff.create',
                'store'   => 'other-staff.store',
                'show'    => 'other-staff.show',
                'edit'    => 'other-staff.edit',
                'update'  => 'other-staff.update',
                'destroy' => 'other-staff.destroy',
            ]);


            /*
            |--------------------------------------------------------------------------
            | SCHOOL MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/students', function () {
                return 'Student Management';
            })->name('students.index');

            Route::get('/attendance/student', function () {
                return view('admin.attendance.student');
            })->name('attendance.student');

            Route::get('/faculty', function () {
                return 'Faculty Management';
            })->name('faculty.index');

            Route::get('/attendance', function () {
                return 'Attendance Management';
            })->name('attendance.index');

            Route::get('/fees', function () {
                return 'Fees Management';
            })->name('fees.index');

            Route::get('/exam', function () {
                return 'Exam Management';
            })->name('exam.index');

            Route::get('/results', function () {
                return 'Result Management';
            })->name('results.index');

            Route::get('/transport', function () {
                return 'Transport Management';
            })->name('transport.index');

            Route::get('/meals', function () {
                return 'Meal Management';
            })->name('meals.index');

            Route::get('/payroll', function () {
                return 'Payroll Management';
            })->name('payroll.index');

            Route::get('/sports', function () {
                return 'Sports Management';
            })->name('sports.index');

            Route::get('/scholarship', function () {
                return 'Scholarship Management';
            })->name('scholarship.index');

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


/*
|--------------------------------------------------------------------------
| CLASS MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::resource(
    'admin/classes',
    SchoolClassController::class
)->names('admin.classes');


/*
|--------------------------------------------------------------------------
| SUBJECT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::resource(
    'admin/subjects',
    SubjectController::class
)->names('admin.subjects');