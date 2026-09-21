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
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\ClassTeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Admin\StudentDocumentsController;
use App\Http\Controllers\Admin\StudentGeneralRegisterController;
use App\Http\Controllers\Admin\StudentHealthController;
use App\Http\Controllers\Admin\BonafideCertificateController;
use App\Http\Controllers\Admin\LocationController;

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

            /* TWO FACTOR AUTHENTICATION */

            Route::get('/2fa/setup', [
                TwoFactorController::class,
                'showSetup'
            ])->name('2fa.setup');

            Route::post('/2fa/verify', [
                TwoFactorController::class,
                'verify'
            ])->name('2fa.verify');

            /* DASHBOARD */

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

            Route::get('/notices', [NoticeController::class, 'index'])
                ->name('notices.index')
                ->middleware('permission:notices.view');

            Route::get('/notices/create', [NoticeController::class, 'create'])
                ->name('notices.create')
                ->middleware('permission:notices.create');

            Route::post('/notices', [NoticeController::class, 'store'])
                ->name('notices.store')
                ->middleware('permission:notices.create');

            Route::get('/notices/{notice}/edit', [NoticeController::class, 'edit'])
                ->name('notices.edit')
                ->middleware('permission:notices.edit');

            Route::put('/notices/{notice}', [NoticeController::class, 'update'])
                ->name('notices.update')
                ->middleware('permission:notices.edit');

            Route::delete('/notices/{notice}', [NoticeController::class, 'destroy'])
                ->name('notices.destroy')
                ->middleware('permission:notices.delete');

            Route::get('/notices/{notice}', [NoticeController::class, 'show'])
                ->name('notices.show')
                ->middleware('permission:notices.view');

            /*
            |--------------------------------------------------------------------------
            | LIBRARY - BOOKS
            |--------------------------------------------------------------------------
            */

            Route::get('/library/books', [BookController::class, 'index'])
                ->name('library.books.index')
                ->middleware('permission:library.view');

            Route::get('/library/books/create', [BookController::class, 'create'])
                ->name('library.books.create')
                ->middleware('permission:library.books');

            Route::post('/library/books', [BookController::class, 'store'])
                ->name('library.books.store')
                ->middleware('permission:library.books');

            Route::get('/library/books/{book}/edit', [BookController::class, 'edit'])
                ->name('library.books.edit')
                ->middleware('permission:library.books');

            Route::put('/library/books/{book}', [BookController::class, 'update'])
                ->name('library.books.update')
                ->middleware('permission:library.books');

            Route::delete('/library/books/{book}', [BookController::class, 'destroy'])
                ->name('library.books.destroy')
                ->middleware('permission:library.books');

            Route::get('/library/books/{book}', [BookController::class, 'show'])
                ->name('library.books.show')
                ->middleware('permission:library.view');

            /* LIBRARY - ISSUE */

            Route::get('/library/issues', [BookIssueController::class, 'index'])
                ->name('library.issues.index')
                ->middleware('permission:library.issue');

            Route::get('/library/issues/create', [BookIssueController::class, 'create'])
                ->name('library.issues.create')
                ->middleware('permission:library.issue');

            Route::post('/library/issues', [BookIssueController::class, 'store'])
                ->name('library.issues.store')
                ->middleware('permission:library.issue');

            /* LIBRARY - RETURNS */

            Route::get('/library/returns', [BookIssueController::class, 'returns'])
                ->name('library.returns.index')
                ->middleware('permission:library.return');

            Route::post('/library/issues/{issue}/return', [BookIssueController::class, 'returnBook'])
                ->name('library.issues.return')
                ->middleware('permission:library.return');

            /* LIBRARY - FINES */

            Route::get('/library/fines', [BookIssueController::class, 'fines'])
                ->name('library.fines.index')
                ->middleware('permission:library.fines');

            Route::patch('/library/fines/{issue}/status', [BookIssueController::class, 'updateFineStatus'])
                ->name('library.fines.status')
                ->middleware('permission:library.fines');

            /* LIBRARY - LIBRARIAN */

            Route::get('/library/librarian', [LibrarianController::class, 'index'])
                ->name('library.librarian.index')
                ->middleware('permission:library.view');

            Route::get('/library/librarian/{librarian}', [LibrarianController::class, 'show'])
                ->name('library.librarian.show')
                ->middleware('permission:library.view');

            /* LIBRARY - REPORTS */

            Route::get('/library/reports', [LibraryReportController::class, 'index'])
                ->name('library.reports.index')
                ->middleware('permission:library.reports');

            Route::get('/library/reports/pdf', [LibraryReportDownloadController::class, 'pdf'])
                ->name('library.reports.pdf')
                ->middleware('permission:library.reports');

            Route::get('/library/reports/excel', [LibraryReportDownloadController::class, 'excel'])
                ->name('library.reports.excel')
                ->middleware('permission:library.reports');

            /* OTHER STAFF */

            Route::resource('other-staff', OtherStaffController::class)
                ->names([
                    'index' => 'other-staff.index',
                    'create' => 'other-staff.create',
                    'store' => 'other-staff.store',
                    'show' => 'other-staff.show',
                    'edit' => 'other-staff.edit',
                    'update' => 'other-staff.update',
                    'destroy' => 'other-staff.destroy',
                ])
                ->middleware('permission:staff.view');

            /* ROLES & PERMISSIONS */

            Route::get('/settings/roles', [RolePermissionController::class, 'index'])
                ->name('settings.roles.index')
                ->middleware('permission:roles.view');

            Route::get('/settings/roles/create', [RolePermissionController::class, 'create'])
                ->name('settings.roles.create')
                ->middleware('permission:roles.manage');

            Route::post('/settings/roles', [RolePermissionController::class, 'store'])
                ->name('settings.roles.store')
                ->middleware('permission:roles.manage');

            Route::get('/settings/roles/{role}/edit', [RolePermissionController::class, 'edit'])
                ->name('settings.roles.edit')
                ->middleware('permission:roles.manage');

            Route::put('/settings/roles/{role}', [RolePermissionController::class, 'update'])
                ->name('settings.roles.update')
                ->middleware('permission:roles.manage');

            Route::delete('/settings/roles/{role}', [RolePermissionController::class, 'destroy'])
                ->name('settings.roles.destroy')
                ->middleware('permission:roles.manage');

            /* USER ACCOUNT MANAGEMENT */

            Route::resource('settings/users', UserManagementController::class)
                ->names([
                    'index' => 'settings.users.index',
                    'create' => 'settings.users.create',
                    'store' => 'settings.users.store',
                    'show' => 'settings.users.show',
                    'edit' => 'settings.users.edit',
                    'update' => 'settings.users.update',
                    'destroy' => 'settings.users.destroy',
                ])
                ->middleware('permission:roles.manage');

            /*
            |--------------------------------------------------------------------------
            | STUDENTS
            |--------------------------------------------------------------------------
            */

            Route::get('/students', [StudentController::class, 'index'])
                ->name('students.index')
                ->middleware('permission:students.view');

            Route::get('/student-profile', [StudentProfileController::class, 'index'])
                ->name('student-profile.index');

            Route::get('/student-profile/search', [StudentProfileController::class, 'search'])
                ->name('student-profile.search');

            Route::get('/student-documents', [StudentDocumentsController::class, 'index'])
                ->name('student-documents.index');

            Route::get('/student-documents/{student}', [StudentDocumentsController::class, 'show'])
                ->name('student-documents.show');

            Route::get('/student-health', [StudentHealthController::class, 'index'])
                ->name('student-health.index');

            Route::get('/student-health/{student}', [StudentHealthController::class, 'show'])
                ->name('student-health.show');

            Route::get('/student-general-register', [StudentGeneralRegisterController::class, 'index'])
                ->name('student-general-register.index');

            Route::get('/student-general-register/print', [StudentGeneralRegisterController::class, 'print'])
                ->name('student-general-register.print');

            Route::get('/student-general-register/{student}', [StudentGeneralRegisterController::class, 'show'])
                ->name('student-general-register.show');

            /* LOCATION API */

            Route::get('/locations/states', [LocationController::class, 'states'])
                ->name('locations.states');

            Route::get('/locations/districts', [LocationController::class, 'districts'])
                ->name('locations.districts');

            /* BONAFIDE CERTIFICATE */

            Route::prefix('bonafide-certificate')
                ->name('bonafide.')
                ->group(function () {
                    Route::get('/', [BonafideCertificateController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [BonafideCertificateController::class, 'create'])
                        ->name('create');

                    Route::post('/', [BonafideCertificateController::class, 'store'])
                        ->name('store');

                    Route::get('/{bonafide}/edit', [BonafideCertificateController::class, 'edit'])
                        ->name('edit');

                    Route::get('/{bonafide}', [BonafideCertificateController::class, 'show'])
                        ->name('show');

                    Route::put('/{bonafide}', [BonafideCertificateController::class, 'update'])
                        ->name('update');

                    Route::delete('/{bonafide}', [BonafideCertificateController::class, 'destroy'])
                        ->name('destroy');
                });

            /* STUDENT ATTENDANCE */

            Route::get('/attendance/student', function () {
                return view('admin.attendance.student');
            })
                ->name('attendance.student')
                ->middleware('permission:attendance.view');

            /* FACULTY */

            Route::get('/faculty', [TeacherController::class, 'index'])
                ->name('faculty.index')
                ->middleware('permission:faculty.view');

            Route::resource('teachers', TeacherController::class);

            /* CLASS TEACHER ASSIGNMENT */

            Route::prefix('teachers/assign-class')
                ->name('teachers.assign-class.')
                ->group(function () {
                    Route::get('/', [ClassTeacherController::class, 'index'])->name('index');
                    Route::get('/create', [ClassTeacherController::class, 'create'])->name('create');
                    Route::post('/', [ClassTeacherController::class, 'store'])->name('store');
                    Route::get('/{assignment}/edit', [ClassTeacherController::class, 'edit'])->name('edit');
                    Route::put('/{assignment}', [ClassTeacherController::class, 'update'])->name('update');
                    Route::delete('/{assignment}', [ClassTeacherController::class, 'destroy'])->name('destroy');
                });

            /* TEACHER SALARY / PAYROLL */

            Route::prefix('teachers/salary')
                ->name('teachers.salary.')
                ->group(function () {
                    Route::get('/', [TeacherSalaryController::class, 'index'])->name('index');
                    Route::get('/create', [TeacherSalaryController::class, 'create'])->name('create');
                    Route::post('/', [TeacherSalaryController::class, 'store'])->name('store');
                    Route::get('/{teacherSalary}', [TeacherSalaryController::class, 'show'])->name('show');
                    Route::get('/{teacherSalary}/edit', [TeacherSalaryController::class, 'edit'])->name('edit');
                    Route::put('/{teacherSalary}', [TeacherSalaryController::class, 'update'])->name('update');
                    Route::delete('/{teacherSalary}', [TeacherSalaryController::class, 'destroy'])->name('destroy');
                });

            Route::get('/payroll', [TeacherSalaryController::class, 'index'])
                ->name('payroll.index');

            /* TEACHER REPORTS */

            Route::get('/teachers/reports', [TeacherReportController::class, 'index'])
                ->name('teachers.reports.index');

            Route::get('/teachers/reports/{teacher}', [TeacherReportController::class, 'show'])
                ->name('teachers.reports.show');

            Route::get('/teachers/reports/{teacher}/pdf', [TeacherReportController::class, 'pdf'])
                ->name('teachers.reports.pdf');

            Route::get('/teachers/reports/{teacher}/excel', [TeacherReportController::class, 'excel'])
                ->name('teachers.reports.excel');

            /* TIMETABLE */

            Route::resource('timetable', TimetableController::class)
                ->except(['show']);

            Route::get('/timetable/class', [TimetableController::class, 'classTimetable'])
                ->name('timetable.class');

            Route::get('/timetable/class/pdf', [TimetableController::class, 'classPdf'])
                ->name('timetable.class.pdf');

            Route::get('/timetable/class/excel', [TimetableController::class, 'classExcel'])
                ->name('timetable.class.excel');

            Route::get('/timetable/teacher', [TimetableController::class, 'teacherTimetable'])
                ->name('timetable.teacher');

            Route::get('/timetable/next-time', [TimetableController::class, 'nextTime'])
                ->name('timetable.next-time');

            /* GENERAL SCHOOL MANAGEMENT PLACEHOLDERS */

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

            Route::get('/library', function () {
                return 'Library Management';
            })->name('library.index');

            Route::get('/transport', function () {
                return 'Transport Management';
            })->name('transport.index');

            Route::get('/meals', function () {
                return 'Meal Management';
            })->name('meals.index');

            Route::get('/sports', function () {
                return 'Sports Management';
            })->name('sports.index');

            Route::get('/scholarship', function () {
                return 'Scholarship Management';
            })->name('scholarship.index');

            Route::get('/settings', function () {
                return 'Settings';
            })->name('settings.index');

            /* CLASS / SUBJECT MANAGEMENT */

            Route::resource('classes', SchoolClassController::class)
                ->names('classes');

            Route::resource('subjects', SubjectController::class)
                ->names('subjects');

            /* LOGOUT */

            Route::post('/logout', [
                LoginController::class,
                'logout'
            ])->name('logout');
        });
    });
