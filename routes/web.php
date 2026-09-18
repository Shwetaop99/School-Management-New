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
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookIssueController;
use App\Http\Controllers\Admin\OtherStaffController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\LibraryReportController;
use App\Http\Controllers\Admin\LibraryReportDownloadController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserManagementController;

/*
|--------------------------------------------------------------------------
| Class Management
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
            ])
                ->name('dashboard')
                ->middleware('permission:dashboard.view');


            /*
            |--------------------------------------------------------------------------
            | NOTICE MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/notices', [
                NoticeController::class,
                'index'
            ])
                ->name('notices.index')
                ->middleware('permission:notices.view');

            Route::get('/notices/create', [
                NoticeController::class,
                'create'
            ])
                ->name('notices.create')
                ->middleware('permission:notices.create');

            Route::post('/notices', [
                NoticeController::class,
                'store'
            ])
                ->name('notices.store')
                ->middleware('permission:notices.create');

            Route::get('/notices/{notice}/edit', [
                NoticeController::class,
                'edit'
            ])
                ->name('notices.edit')
                ->middleware('permission:notices.edit');

            Route::put('/notices/{notice}', [
                NoticeController::class,
                'update'
            ])
                ->name('notices.update')
                ->middleware('permission:notices.edit');

            Route::delete('/notices/{notice}', [
                NoticeController::class,
                'destroy'
            ])
                ->name('notices.destroy')
                ->middleware('permission:notices.delete');

            Route::get('/notices/{notice}', [
                NoticeController::class,
                'show'
            ])
                ->name('notices.show')
                ->middleware('permission:notices.view');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - BOOKS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/books', [
                BookController::class,
                'index'
            ])
                ->name('library.books.index')
                ->middleware('permission:library.view');

            Route::get('/library/books/create', [
                BookController::class,
                'create'
            ])
                ->name('library.books.create')
                ->middleware('permission:library.books');

            Route::post('/library/books', [
                BookController::class,
                'store'
            ])
                ->name('library.books.store')
                ->middleware('permission:library.books');

            Route::get('/library/books/{book}/edit', [
                BookController::class,
                'edit'
            ])
                ->name('library.books.edit')
                ->middleware('permission:library.books');

            Route::put('/library/books/{book}', [
                BookController::class,
                'update'
            ])
                ->name('library.books.update')
                ->middleware('permission:library.books');

            Route::delete('/library/books/{book}', [
                BookController::class,
                'destroy'
            ])
                ->name('library.books.destroy')
                ->middleware('permission:library.books');

            // Keep this LAST because {book} catches the book ID.
            Route::get('/library/books/{book}', [
                BookController::class,
                'show'
            ])
                ->name('library.books.show')
                ->middleware('permission:library.view');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - ISSUE BOOK
            |--------------------------------------------------------------------------
            */

            Route::get('/library/issues', [
                BookIssueController::class,
                'index'
            ])
                ->name('library.issues.index')
                ->middleware('permission:library.issue');

            Route::get('/library/issues/create', [
                BookIssueController::class,
                'create'
            ])
                ->name('library.issues.create')
                ->middleware('permission:library.issue');

            Route::post('/library/issues', [
                BookIssueController::class,
                'store'
            ])
                ->name('library.issues.store')
                ->middleware('permission:library.issue');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - RETURNS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/returns', [
                BookIssueController::class,
                'returns'
            ])
                ->name('library.returns.index')
                ->middleware('permission:library.return');

            Route::post('/library/issues/{issue}/return', [
                BookIssueController::class,
                'returnBook'
            ])
                ->name('library.issues.return')
                ->middleware('permission:library.return');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - FINES
            |--------------------------------------------------------------------------
            */

            Route::get('/library/fines', [
                BookIssueController::class,
                'fines'
            ])
                ->name('library.fines.index')
                ->middleware('permission:library.fines');

            Route::patch('/library/fines/{issue}/status', [
                BookIssueController::class,
                'updateFineStatus'
            ])
                ->name('library.fines.status')
                ->middleware('permission:library.fines');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - LIBRARIAN
            |--------------------------------------------------------------------------
            */

            Route::get('/library/librarian', [
                LibrarianController::class,
                'index'
            ])
                ->name('library.librarian.index')
                ->middleware('permission:library.view');

            Route::get('/library/librarian/{librarian}', [
                LibrarianController::class,
                'show'
            ])
                ->name('library.librarian.show')
                ->middleware('permission:library.view');


            /*
            |--------------------------------------------------------------------------
            | LIBRARY - REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/reports', [
                LibraryReportController::class,
                'index'
            ])
                ->name('library.reports.index')
                ->middleware('permission:library.reports');

            Route::get('/library/reports/pdf', [
                LibraryReportDownloadController::class,
                'pdf'
            ])
                ->name('library.reports.pdf')
                ->middleware('permission:library.reports');

            Route::get('/library/reports/excel', [
                LibraryReportDownloadController::class,
                'excel'
            ])
                ->name('library.reports.excel')
                ->middleware('permission:library.reports');


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
                ])
                ->middleware('permission:staff.view');


            /*
            |--------------------------------------------------------------------------
            | USER ROLES & PERMISSIONS
            |--------------------------------------------------------------------------
            */

            Route::get('/settings/roles', [
                RolePermissionController::class,
                'index'
            ])
                ->name('settings.roles.index')
                ->middleware('permission:roles.view');

            Route::get('/settings/roles/create', [
                RolePermissionController::class,
                'create'
            ])
                ->name('settings.roles.create')
                ->middleware('permission:roles.manage');

            Route::post('/settings/roles', [
                RolePermissionController::class,
                'store'
            ])
                ->name('settings.roles.store')
                ->middleware('permission:roles.manage');

            Route::get('/settings/roles/{role}/edit', [
                RolePermissionController::class,
                'edit'
            ])
                ->name('settings.roles.edit')
                ->middleware('permission:roles.manage');

            Route::put('/settings/roles/{role}', [
                RolePermissionController::class,
                'update'
            ])
                ->name('settings.roles.update')
                ->middleware('permission:roles.manage');

            Route::delete('/settings/roles/{role}', [
                RolePermissionController::class,
                'destroy'
            ])
                ->name('settings.roles.destroy')
                ->middleware('permission:roles.manage');


            /*
            |--------------------------------------------------------------------------
            | USER ACCOUNT MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::resource('settings/users', UserManagementController::class)
                ->names([
                    'index'   => 'settings.users.index',
                    'create'  => 'settings.users.create',
                    'store'   => 'settings.users.store',
                    'show'    => 'settings.users.show',
                    'edit'    => 'settings.users.edit',
                    'update'  => 'settings.users.update',
                    'destroy' => 'settings.users.destroy',
                ])
                ->middleware('permission:roles.manage');


            /*
            |--------------------------------------------------------------------------
            | SCHOOL MANAGEMENT
            |--------------------------------------------------------------------------
            */

            // Students
            Route::get('/students', function () {
                return 'Student Management';
            })
                ->name('students.index')
                ->middleware('permission:students.view');


            // Student Attendance
            Route::get('/attendance/student', function () {
                return view('admin.attendance.student');
            })
                ->name('attendance.student')
                ->middleware('permission:attendance.view');


            // Faculty
            Route::get('/faculty', function () {
                return 'Faculty Management';
            })
                ->name('faculty.index')
                ->middleware('permission:faculty.view');


            // Timetable
            // No dedicated timetable permission exists yet.
            // This route remains authenticated until that permission is created.
            Route::get('/timetable', function () {
                return 'Time Table';
            })->name('timetable.index');


            // Attendance
            Route::get('/attendance', function () {
                return 'Attendance Management';
            })
                ->name('attendance.index')
                ->middleware('permission:attendance.view');


            // Fees
            Route::get('/fees', function () {
                return 'Fees Management';
            })
                ->name('fees.index')
                ->middleware('permission:fees.view');


            // Exam
            Route::get('/exam', function () {
                return 'Exam Management';
            })
                ->name('exam.index')
                ->middleware('permission:exams.view');


            // Results
            Route::get('/results', function () {
                return 'Result Management';
            })
                ->name('results.index')
                ->middleware('permission:reports.view');


            // Transport
            // No dedicated transport permission exists yet.
            Route::get('/transport', function () {
                return 'Transport Management';
            })->name('transport.index');


            // Meals
            // No dedicated meals permission exists yet.
            Route::get('/meals', function () {
                return 'Meal Management';
            })->name('meals.index');


            // Payroll
            // No dedicated payroll permission exists yet.
            Route::get('/payroll', function () {
                return 'Payroll Management';
            })->name('payroll.index');


            // Sports
            // No dedicated sports permission exists yet.
            Route::get('/sports', function () {
                return 'Sports Management';
            })->name('sports.index');


            // Scholarship
            // No dedicated scholarship permission exists yet.
            Route::get('/scholarship', function () {
                return 'Scholarship Management';
            })->name('scholarship.index');


            // Settings
            Route::get('/settings', function () {
                return 'Settings';
            })
                ->name('settings.index')
                ->middleware('permission:settings.view');

        });


        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', [
            LoginController::class,
            'logout'
        ])
            ->middleware('auth')
            ->name('logout');

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
