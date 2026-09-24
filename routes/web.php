<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Authentication
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
<<<<<<< HEAD
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\StaffCategoryController;
use App\Http\Controllers\Admin\LeaveApplicationController;
use App\Http\Controllers\Admin\ClassTeacherController;
=======
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookIssueController;
use App\Http\Controllers\Admin\OtherStaffController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\LibraryReportController;
use App\Http\Controllers\Admin\LibraryReportDownloadController;

/*
|--------------------------------------------------------------------------
| Class Management
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Class\SchoolClassController;
use App\Http\Controllers\Class\SubjectController;
>>>>>>> d6de0b3e2e678ebd398c8c6c042aa18e164b7995


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
            | Library Main Page
            |--------------------------------------------------------------------------
            */

        


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - BOOKS
            |--------------------------------------------------------------------------
            */

            // Books List
            Route::get('/library/books', [
                BookController::class,
                'index'
            ])->name('library.books.index');


            // Add Book Page
            Route::get('/library/books/create', [
                BookController::class,
                'create'
            ])->name('library.books.create');


            // Store Book
            Route::post('/library/books', [
                BookController::class,
                'store'
            ])->name('library.books.store');


            // Edit Book Page
            Route::get('/library/books/{book}/edit', [
                BookController::class,
                'edit'
            ])->name('library.books.edit');


            // Update Book
            Route::put('/library/books/{book}', [
                BookController::class,
                'update'
            ])->name('library.books.update');


            // Delete Book
            Route::delete('/library/books/{book}', [
                BookController::class,
                'destroy'
            ])->name('library.books.destroy');


            // View Book
            // Keep this LAST because {book} catches the book ID.
            Route::get('/library/books/{book}', [
                BookController::class,
                'show'
            ])->name('library.books.show');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - ISSUE BOOK
            |--------------------------------------------------------------------------
            */

            // Issue / Transaction List
            Route::get('/library/issues', [
                BookIssueController::class,
                'index'
            ])->name('library.issues.index');


            // Issue Book Page
            Route::get('/library/issues/create', [
                BookIssueController::class,
                'create'
            ])->name('library.issues.create');


            // Store Issue
            Route::post('/library/issues', [
                BookIssueController::class,
                'store'
            ])->name('library.issues.store');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - RETURNS
            |--------------------------------------------------------------------------
            */

            // Return Books Page
            Route::get('/library/returns', [
                BookIssueController::class,
                'returns'
            ])->name('library.returns.index');


            // Process Book Return
            Route::post('/library/issues/{issue}/return', [
                BookIssueController::class,
                'returnBook'
            ])->name('library.issues.return');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - FINES
            |--------------------------------------------------------------------------
            */

            // Fines List
            Route::get('/library/fines', [
                BookIssueController::class,
                'fines'
            ])->name('library.fines.index');


            // Update Fine Payment Status
            Route::patch('/library/fines/{issue}/status', [
                BookIssueController::class,
                'updateFineStatus'
            ])->name('library.fines.status');

            Route::get('/library/librarian', [LibrarianController::class, 'index'])
    ->name('library.librarian.index');

    Route::get('/library/librarian/{librarian}', [LibrarianController::class, 'show'])
    ->name('library.librarian.show');

    Route::get('/library/reports', [LibraryReportController::class, 'index'])
    ->name('library.reports.index');

    Route::get('/library/reports/pdf', [LibraryReportDownloadController::class, 'pdf'])
    ->name('library.reports.pdf');

Route::get('/library/reports/excel', [LibraryReportDownloadController::class, 'excel'])
    ->name('library.reports.excel');

            /*
|--------------------------------------------------------------------------
| OTHER STAFF MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::resource('other-staff', OtherStaffController::class)
    ->names([
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

            // Students
            Route::get('/students', function () {
                return 'Student Management';
            })->name('students.index');


            // Student Attendance
            Route::get('/attendance/student', function () {
                return view('admin.attendance.student');
            })->name('attendance.student');


            // Faculty
            Route::get('/faculty', function () {
                return 'Faculty Management';
            })->name('faculty.index');


            // Timetable
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


            // Transport
            Route::get('/transport', function () {
                return 'Transport Management';
            })->name('transport.index');


            // Meals
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
