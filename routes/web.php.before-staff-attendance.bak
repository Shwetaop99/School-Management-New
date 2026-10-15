<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FeePaymentController;

/*
|--------------------------------------------------------------------------
| SPORTS CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Sports\GameController;
use App\Http\Controllers\Sports\AchievementController;
use App\Http\Controllers\Sports\EquipmentController;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\ClassTeacherController;
use App\Http\Controllers\Admin\StudentFeeController;

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Admin\StudentDocumentsController;
use App\Http\Controllers\Admin\StudentSupplyKitController;

use App\Http\Controllers\Admin\SchoolLeavingCertificateController;
use App\Http\Controllers\Admin\BonafideCertificateController;

use App\Http\Controllers\Admin\AgeReportController;
use App\Http\Controllers\Admin\IdCardTemplateController;
use App\Http\Controllers\Admin\IdCardController;

use App\Http\Controllers\Admin\AttendanceController;

use App\Http\Controllers\Admin\StaffCategoryController;
use App\Http\Controllers\Admin\LeaveApplicationController;
use App\Http\Controllers\Admin\NoticeController;

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookIssueController;
use App\Http\Controllers\Admin\OtherStaffController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\LibraryReportController;
use App\Http\Controllers\Admin\LibraryReportDownloadController;

use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SchoolSettingController;

use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamClassController;
use App\Http\Controllers\Admin\ExamSubjectController;
use App\Http\Controllers\Admin\ExamSessionController;
use App\Http\Controllers\Admin\ExamHolidayController;
use App\Http\Controllers\Admin\ExamScheduleController;
use App\Http\Controllers\Admin\FeeTypeController;

use App\Http\Controllers\Admin\KitTemplateController;
use App\Http\Controllers\Admin\SupplyItemController;

/*
|--------------------------------------------------------------------------
| MEAL CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Meal\MealItemController;
use App\Http\Controllers\Meal\MealStockLogController;
use App\Http\Controllers\Meal\MealStockTransactionController;

/*
|--------------------------------------------------------------------------
| CLASS MANAGEMENT CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Class\SchoolClassController;
use App\Http\Controllers\Class\SubjectController;
use App\Http\Controllers\Admin\FeeStructureController;

/*
|--------------------------------------------------------------------------
| TRANSPORT CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Transport\TransportRecordController;
use App\Http\Controllers\Transport\TransportRouteController;
use App\Http\Controllers\Transport\TransportVehicleController;


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

        Route::get('/teachers/attendance/excel', [
            TeacherAttendanceController::class,
            'excel'
        ])->name('teachers.attendance.excel');


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
            | TEACHER SALARY
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

                    Route::post('/', [
                        TeacherSalaryController::class,
                        'store'
                    ])->name('store');

                    Route::get('/attendance-data', [
                        TeacherSalaryController::class,
                        'attendanceData'
                    ])->name('attendance-data');

                    Route::get('/{salary}', [
                        TeacherSalaryController::class,
                        'show'
                    ])->name('show');

                    Route::get('/{salary}/edit', [
                        TeacherSalaryController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{salary}', [
                        TeacherSalaryController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{salary}', [
                        TeacherSalaryController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/reports', [
                ReportsController::class,
                'index'
            ])->name('reports.index');


            /*
            |--------------------------------------------------------------------------
            | LOCATIONS
            |--------------------------------------------------------------------------
            */

            Route::get('/locations/states', [
                LocationController::class,
                'states'
            ])->name('locations.states');

            Route::get('/locations/districts', [
                LocationController::class,
                'districts'
            ])->name('locations.districts');

            Route::get('/locations/tehsils', [
                LocationController::class,
                'tehsils'
            ])->name('locations.tehsils');

            Route::get('/locations', [
                LocationController::class,
                'locations'
            ])->name('locations.locations');


            /*
            |--------------------------------------------------------------------------
            | STUDENTS
            |--------------------------------------------------------------------------
            */

            Route::get('/students/search', [
                StudentController::class,
                'search'
            ])->name('students.search');

            Route::get('/students/next-roll-number', [
                StudentController::class,
                'nextRollNumber'
            ])->name('students.next-roll-number');

            Route::get('/students/class-wise', [
                StudentController::class,
                'classWise'
            ])->name('students.class-wise');

            Route::get('/students/class-wise/print', [
                StudentController::class,
                'classWisePrint'
            ])->name('students.class-wise.print');

            Route::resource(
                'students',
                StudentController::class
            );


            /*
            |--------------------------------------------------------------------------
            | STUDENT PROFILE
            |--------------------------------------------------------------------------
            */

            Route::get('/students/profile', [
                StudentProfileController::class,
                'index'
            ])->name('students.profile');

            Route::get('/students/profile/search', [
                StudentProfileController::class,
                'search'
            ])->name('student-profile.search');

            Route::get('/test-profile', function () {
                return 'Profile route is working';
            })->name('test.profile');


            /*
            |--------------------------------------------------------------------------
            | STUDENT DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get('/students/documents', [
                StudentDocumentsController::class,
                'index'
            ])->name('students.documents');


            /*
            |--------------------------------------------------------------------------
            | STUDENT SUPPLY KIT
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'students/supplies',
                StudentSupplyKitController::class
            )->names('students.supplies');


            /*
            |--------------------------------------------------------------------------
            | NOTICES
            |--------------------------------------------------------------------------
            */

            Route::get('/notices', [
                NoticeController::class,
                'index'
            ])->name('notices.index');

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
            | BONAFIDE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'bonafide',
                BonafideCertificateController::class
            );


            /*
            |--------------------------------------------------------------------------
            | SCHOOL LEAVING CERTIFICATE
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'school-leaving-certificate',
                SchoolLeavingCertificateController::class
            );

            Route::get(
                '/school-leaving-certificate/{schoolLeavingCertificate}/print',
                [
                    SchoolLeavingCertificateController::class,
                    'print'
                ]
            )->name('school-leaving-certificate.print');


            /*
            |--------------------------------------------------------------------------
            | AGE REPORT
            |--------------------------------------------------------------------------
            */

            Route::get('/age-report', [
                AgeReportController::class,
                'index'
            ])->name('age-report.index');

            Route::get('/age-report/print', [
                AgeReportController::class,
                'print'
            ])->name('age-report.print');


            /*
            |--------------------------------------------------------------------------
            | ID CARD
            |--------------------------------------------------------------------------
            */

            Route::get('/students/id-card', [
                IdCardController::class,
                'index'
            ])->name('students.id-card');

            Route::prefix('id-card')
                ->name('id-card.')
                ->group(function () {

                    Route::get('/', [
                        IdCardController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        IdCardController::class,
                        'create'
                    ])->name('create');

                    Route::get('/search', [
                        IdCardController::class,
                        'search'
                    ])->name('search');

                    Route::post('/', [
                        IdCardController::class,
                        'store'
                    ])->name('store');

                    Route::get('/templates', [
                        IdCardTemplateController::class,
                        'index'
                    ])->name('templates.index');

                    Route::get('/templates/create', [
                        IdCardTemplateController::class,
                        'create'
                    ])->name('templates.create');

                    Route::post('/templates', [
                        IdCardTemplateController::class,
                        'store'
                    ])->name('templates.store');

                    Route::get('/templates/{template}/edit', [
                        IdCardTemplateController::class,
                        'edit'
                    ])->name('templates.edit');

                    Route::put('/templates/{template}', [
                        IdCardTemplateController::class,
                        'update'
                    ])->name('templates.update');

                    Route::post('/templates/{template}/analyze', [
                        IdCardTemplateController::class,
                        'analyze'
                    ])->name('templates.analyze');

                    Route::get('/templates/{template}/analysis', [
                        IdCardTemplateController::class,
                        'analysis'
                    ])->name('templates.analysis');

                    Route::post('/templates/{template}/save-positions', [
                        IdCardTemplateController::class,
                        'savePositions'
                    ])->name('templates.save-positions');

                    Route::patch('/templates/{template}/toggle-status', [
                        IdCardTemplateController::class,
                        'toggleStatus'
                    ])->name('templates.toggle-status');

                    Route::delete('/templates/{template}', [
                        IdCardTemplateController::class,
                        'destroy'
                    ])->name('templates.destroy');

                    Route::get('/{id}/print', [
                        IdCardController::class,
                        'print'
                    ])->name('print');

                    Route::get('/{id}', [
                        IdCardController::class,
                        'show'
                    ])->name('show');

                    Route::delete('/{id}', [
                        IdCardController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | TEACHERS - ASSIGN CLASS
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

            Route::resource(
                'teachers',
                TeacherController::class
            );


            /*
            |--------------------------------------------------------------------------
            | TIMETABLE MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/timetable', [
                TimetableController::class,
                'index'
            ])->name('timetable.index');

            Route::get('/timetable/create', [
                TimetableController::class,
                'create'
            ])->name('timetable.create');

            Route::post('/timetable', [
                TimetableController::class,
                'store'
            ])->name('timetable.save');

            Route::get('/timetable/teacher', [
                TimetableController::class,
                'teacherTimetable'
            ])->name('timetable.teacher');

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

            Route::get('/timetable/{timetable}', [
                TimetableController::class,
                'show'
            ])->name('timetable.show');

            Route::get('/timetable/{timetable}/edit', [
                TimetableController::class,
                'edit'
            ])->name('timetable.edit');

            Route::put('/timetable/{timetable}', [
                TimetableController::class,
                'update'
            ])->name('timetable.update');

            Route::delete('/timetable/{timetable}', [
                TimetableController::class,
                'destroy'
            ])->name('timetable.destroy');


            /*
            |--------------------------------------------------------------------------
            | OTHER STAFF
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'other-staff',
                OtherStaffController::class
            )
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
            | TEACHER REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/teachers/reports', [
                TeacherReportController::class,
                'index'
            ])->name('teachers.reports.index');

            Route::get('/teachers/reports/{teacher}/pdf', [
                TeacherReportController::class,
                'pdf'
            ])->name('teachers.reports.pdf');

            Route::get('/teachers/reports/{teacher}/excel', [
                TeacherReportController::class,
                'excel'
            ])->name('teachers.reports.excel');

            Route::get('/teachers/reports/{teacher}', [
                TeacherReportController::class,
                'show'
            ])->name('teachers.reports.show');


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
            | STUDENT ATTENDANCE
            |--------------------------------------------------------------------------
            */

            Route::get('/attendance', [
                AttendanceController::class,
                'index'
            ])->name('attendance.student');

            Route::get('/attendance/sheet', [
                AttendanceController::class,
                'sheet'
            ])->name('attendance.student.sheet');

            Route::post('/attendance', [
                AttendanceController::class,
                'store'
            ])->name('attendance.store');

            Route::get('/attendance/classes', [
                AttendanceController::class,
                'classes'
            ])->name('attendance.classes');

            Route::get('/attendance/sections', [
                AttendanceController::class,
                'sections'
            ])->name('attendance.sections');

            Route::get('/attendance/students', [
                AttendanceController::class,
                'students'
            ])->name('attendance.students');

            Route::get('/attendance/report', [
                AttendanceController::class,
                'report'
            ])->name('attendance.report');

            Route::get('/attendance/report/print', [
                AttendanceController::class,
                'printReport'
            ])->name('attendance.report.print');

            Route::get('/attendance/student/{student}', [
                AttendanceController::class,
                'studentReport'
            ])->name('attendance.student-report');

            Route::get('/attendance/monthly', [
                AttendanceController::class,
                'monthlyReport'
            ])->name('attendance.monthly');

            Route::put('/attendance/{attendance}', [
                AttendanceController::class,
                'update'
            ])->name('attendance.update');

            Route::get('/attendance/pdf', [
                AttendanceController::class,
                'pdf'
            ])->name('attendance.pdf');

            Route::get('/attendance/excel', [
                AttendanceController::class,
                'excel'
            ])->name('attendance.excel');


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
            | SUPPLY ITEMS
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'supply-items',
                SupplyItemController::class
            )->names('supply-items');


            /*
            |--------------------------------------------------------------------------
            | KIT TEMPLATES
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'kit-templates',
                KitTemplateController::class
            )->names('kit-templates');


            /*
            |--------------------------------------------------------------------------
            | STUDENT SUPPLY KIT AJAX
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/student-supply-kits/students/search',
                [
                    StudentSupplyKitController::class,
                    'searchStudents'
                ]
            )->name('student-supply-kits.students.search');

            Route::get(
                '/student-supply-kits/template/items',
                [
                    StudentSupplyKitController::class,
                    'templateItems'
                ]
            )->name('student-supply-kits.template.items');

            Route::get(
                '/student-supply-kits/supply-items',
                [
                    StudentSupplyKitController::class,
                    'supplyItems'
                ]
            )->name('student-supply-kits.supply-items');


            /*
            |--------------------------------------------------------------------------
            | SCHOOL SETTINGS
            |--------------------------------------------------------------------------
            */

            Route::get('/settings', [
                SchoolSettingController::class,
                'index'
            ])->name('settings.index');

            Route::get('/settings/create', [
                SchoolSettingController::class,
                'create'
            ])->name('settings.create');

            Route::post('/settings', [
                SchoolSettingController::class,
                'store'
            ])->name('settings.store');

            Route::get('/settings/edit', [
                SchoolSettingController::class,
                'edit'
            ])->name('settings.edit');

            Route::put('/settings', [
                SchoolSettingController::class,
                'update'
            ])->name('settings.update');

            Route::delete('/settings', [
                SchoolSettingController::class,
                'destroy'
            ])->name('settings.destroy');


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

            Route::resource(
                'settings/users',
                UserManagementController::class
            )
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
            | STUDENT SUPPLY KIT PRINT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/student-supply-kits/{studentSupplyKit}/print',
                [
                    StudentSupplyKitController::class,
                    'print'
                ]
            )->name('student-supply-kits.print');


            /*
            |--------------------------------------------------------------------------
            | STUDENT SUPPLY KIT CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'student-supply-kits',
                StudentSupplyKitController::class
            )->names('student-supply-kits');


            /*
            |--------------------------------------------------------------------------
            | FEES
            |--------------------------------------------------------------------------
            */

/*
|--------------------------------------------------------------------------
| FEES MODULE
|--------------------------------------------------------------------------
*/

Route::prefix('fees')
    ->name('fees.')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | FEE TYPES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'fee-types',
        FeeTypeController::class
    )->names('fee-types');


    /*
    |--------------------------------------------------------------------------
    | FEE STRUCTURE SECTIONS AJAX
    |--------------------------------------------------------------------------
    */

    Route::get(
        'fee-structures/sections/{classId}',
        [
            FeeStructureController::class,
            'sections'
        ]
    )->name('fee-structures.sections');
    
    Route::get(
    'student-fees/{studentFee}/payment',
    [FeePaymentController::class, 'create']
)->name('student-fees.payment.create');

Route::post(
    'student-fees/{studentFee}/payment',
    [FeePaymentController::class, 'store']
)->name('student-fees.payment.store');

    /*
    |--------------------------------------------------------------------------
    | FEE STRUCTURES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'fee-structures',
        FeeStructureController::class
    )->names('fee-structures');


    /*
    |--------------------------------------------------------------------------
    | STUDENT FEES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'student-fees',
        StudentFeeController::class
    )->only([
        'index',
        'create',
        'store',
        'show',
        'destroy',
    ]);


    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    Route::get(
        'payment-history',
        [
            FeePaymentController::class,
            'index'
        ]
    )->name('payment-history.index');


    Route::get(
        'payment-history/{feePayment}',
        [
            FeePaymentController::class,
            'show'
        ]
    )->name('payment-history.show');

});


            /*
            |--------------------------------------------------------------------------
            | EXAM MAIN MENU
            |--------------------------------------------------------------------------
            */

          
            /*
            |--------------------------------------------------------------------------
            | RESULTS MAIN MENU
            |--------------------------------------------------------------------------
            */
/*
|--------------------------------------------------------------------------
| RESULTS MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::prefix('results')->name('results.')->group(function () {

    // Results list
    Route::get('/', [
       \App\Http\Controllers\Admin\ResultController::class,
        'index'
    ])->name('index');

    // Generate result page
    Route::get('/generate', [
       \App\Http\Controllers\Admin\ResultController::class,
        'generate'
    ])->name('generate');

    // Enter marks page
    Route::get('/marks', [
      \App\Http\Controllers\Admin\ResultController::class,
        'marks'
    ])->name('marks');

    // Save marks
    Route::post('/marks', [
        \App\Http\Controllers\Admin\ResultController::class,
        'store'
    ])->name('store');
});

            /*
            |--------------------------------------------------------------------------
            | TRANSPORT MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::prefix('transport')
                ->name('transport.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT DASHBOARD
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/', function () {
                        return view('admin.transport.index');
                    })->name('index');


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT RECORDS
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/records/students-by-class',
                        [
                            TransportRecordController::class,
                            'studentsByClass'
                        ]
                    )->name('records.students-by-class');

                    Route::get(
                        '/records/{transportRecord}/download-pdf',
                        [
                            TransportRecordController::class,
                            'downloadPdf'
                        ]
                    )->name('records.download-pdf');

                    Route::resource(
                        'records',
                        TransportRecordController::class
                    )->parameters([
                        'records' => 'transportRecord',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT ROUTES
                    |--------------------------------------------------------------------------
                    */

                    Route::resource(
                        'routes',
                        TransportRouteController::class
                    )->parameters([
                        'routes' => 'transportRoute',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT VEHICLES
                    |--------------------------------------------------------------------------
                    */

                    Route::resource(
                        'vehicles',
                        TransportVehicleController::class
                    )->parameters([
                        'vehicles' => 'transportVehicle',
                    ]);
                });


            /*
            |--------------------------------------------------------------------------
            | TRANSPORT MAIN MENU
            |--------------------------------------------------------------------------
            */

            Route::get('/transport-menu', function () {
                return 'Transport Management';
            })->name('transport.menu');


            /*
            |--------------------------------------------------------------------------
            | PAYROLL MAIN MENU
            |--------------------------------------------------------------------------
            */

            Route::get('/payroll', function () {
                return 'Payroll Management';
            })->name('payroll.index');


            /*
            |--------------------------------------------------------------------------
            | SPORTS MAIN MENU
            |--------------------------------------------------------------------------
            */

            Route::get('/sports', function () {
                return 'Sports Management';
            })->name('sports.index');


            /*
            |--------------------------------------------------------------------------
            | EXAM MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'exams',
                ExamController::class
            )->names('exams');


            /*
            |--------------------------------------------------------------------------
            | EXAM CLASSES
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/classes')
                ->name('exam-classes.')
                ->group(function () {

                    Route::get('/', [
                        ExamClassController::class,
                        'index'
                    ])->name('index');

                    Route::post('/', [
                        ExamClassController::class,
                        'store'
                    ])->name('store');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM SUBJECTS
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/subjects')
                ->name('exam-subjects.')
                ->group(function () {

                    Route::get('/', [
                        ExamSubjectController::class,
                        'index'
                    ])->name('index');

                    Route::post('/', [
                        ExamSubjectController::class,
                        'store'
                    ])->name('store');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM SESSIONS
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/sessions')
                ->name('exam-sessions.')
                ->group(function () {

                    Route::get('/', [
                        ExamSessionController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ExamSessionController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ExamSessionController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{session}/edit', [
                        ExamSessionController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{session}', [
                        ExamSessionController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{session}', [
                        ExamSessionController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM HOLIDAYS
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/holidays')
                ->name('exam-holidays.')
                ->group(function () {

                    Route::get('/', [
                        ExamHolidayController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ExamHolidayController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ExamHolidayController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{holiday}/edit', [
                        ExamHolidayController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{holiday}', [
                        ExamHolidayController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{holiday}', [
                        ExamHolidayController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM SCHEDULE GENERATION
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule/generate',
                [
                    ExamScheduleController::class,
                    'generateForm'
                ]
            )->name('exam-schedules.generate.form');

            Route::post(
                'exams/{exam}/schedule/generate',
                [
                    ExamScheduleController::class,
                    'generate'
                ]
            )->name('exam-schedules.generate');


            /*
            |--------------------------------------------------------------------------
            | EXAM TIMETABLE
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule',
                [
                    ExamScheduleController::class,
                    'index'
                ]
            )->name('exam-schedules.index');

            Route::get(
                'exams/{exam}/schedule/print',
                [
                    ExamScheduleController::class,
                    'print'
                ]
            )->name('exam-schedules.print');

            Route::get(
                'exams/{exam}/schedule/pdf',
                [
                    ExamScheduleController::class,
                    'pdf'
                ]
            )->name('exam-schedules.pdf');

            Route::get(
                'exams/{exam}/schedule/{schedule}/edit',
                [
                    ExamScheduleController::class,
                    'edit'
                ]
            )->name('exam-schedules.edit');

            Route::put(
                'exams/{exam}/schedule/{schedule}',
                [
                    ExamScheduleController::class,
                    'update'
                ]
            )->name('exam-schedules.update');

            Route::delete(
                'exams/{exam}/schedule/{schedule}',
                [
                    ExamScheduleController::class,
                    'destroy'
                ]
            )->name('exam-schedules.destroy');


            /*
            |--------------------------------------------------------------------------
            | SPORTS - GAMES
            |--------------------------------------------------------------------------
            */

            Route::get('/sports/games', [
                GameController::class,
                'index'
            ])->name('sports.games.index');

            Route::get('/sports/games/create', [
                GameController::class,
                'create'
            ])->name('sports.games.create');

            Route::post('/sports/games', [
                GameController::class,
                'store'
            ])->name('sports.games.store');

            Route::get('/sports/games/export', [
                GameController::class,
                'export'
            ])->name('sports.games.export');

            Route::get('/sports/games/{game}/edit', [
                GameController::class,
                'edit'
            ])->name('sports.games.edit');

            Route::get('/sports/games/{game}', [
                GameController::class,
                'show'
            ])->name('sports.games.show');

            Route::put('/sports/games/{game}', [
                GameController::class,
                'update'
            ])->name('sports.games.update');

            Route::delete('/sports/games/{game}', [
                GameController::class,
                'destroy'
            ])->name('sports.games.destroy');


            /*
            |--------------------------------------------------------------------------
            | SPORTS - ACHIEVEMENTS
            |--------------------------------------------------------------------------
            */

            Route::get('/sports/achievements', [
                AchievementController::class,
                'index'
            ])->name('sports.achievements.index');

            Route::get('/sports/achievements/create', [
                AchievementController::class,
                'create'
            ])->name('sports.achievements.create');

            Route::post('/sports/achievements', [
                AchievementController::class,
                'store'
            ])->name('sports.achievements.store');

            Route::get('/sports/achievements/{achievement}/edit', [
                AchievementController::class,
                'edit'
            ])->name('sports.achievements.edit');

            Route::get('/sports/achievements/{achievement}', [
                AchievementController::class,
                'show'
            ])->name('sports.achievements.show');

            Route::put('/sports/achievements/{achievement}', [
                AchievementController::class,
                'update'
            ])->name('sports.achievements.update');

            Route::delete('/sports/achievements/{achievement}', [
                AchievementController::class,
                'destroy'
            ])->name('sports.achievements.destroy');


            /*
            |--------------------------------------------------------------------------
            | SPORTS - EQUIPMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/sports/equipment', [
                EquipmentController::class,
                'index'
            ])->name('sports.equipment.index');

            Route::get('/sports/equipment/create', [
                EquipmentController::class,
                'create'
            ])->name('sports.equipment.create');

            Route::post('/sports/equipment', [
                EquipmentController::class,
                'store'
            ])->name('sports.equipment.store');

            Route::get('/sports/equipment/{equipment}/edit', [
                EquipmentController::class,
                'edit'
            ])->name('sports.equipment.edit');

            Route::get('/sports/equipment/{equipment}', [
                EquipmentController::class,
                'show'
            ])->name('sports.equipment.show');

            Route::put('/sports/equipment/{equipment}', [
                EquipmentController::class,
                'update'
            ])->name('sports.equipment.update');

            Route::delete('/sports/equipment/{equipment}', [
                EquipmentController::class,
                'destroy'
            ])->name('sports.equipment.destroy');


            /*
            |--------------------------------------------------------------------------
            | SCHOLARSHIP
            |--------------------------------------------------------------------------
            */

            Route::get('/scholarship', function () {
                return 'Scholarship Management';
            })->name('scholarship.index');

        });


        /*
        |--------------------------------------------------------------------------
        | ADMIN LOGOUT
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


/*
|--------------------------------------------------------------------------
| MEAL MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::prefix('admin/meal/items')
    ->name('admin.meal.items.')
    ->group(function () {

        Route::get('/', [
            MealItemController::class,
            'index'
        ])->name('index');

        Route::get('/create', [
            MealItemController::class,
            'create'
        ])->name('create');

        Route::post('/', [
            MealItemController::class,
            'store'
        ])->name('store');

        Route::get('/{mealItem}/edit', [
            MealItemController::class,
            'edit'
        ])->name('edit');

        Route::put('/{mealItem}', [
            MealItemController::class,
            'update'
        ])->name('update');

        Route::delete('/{mealItem}', [
            MealItemController::class,
            'destroy'
        ])->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | MEAL STOCK
        |--------------------------------------------------------------------------
        */

        Route::get('/stock', [
            MealStockTransactionController::class,
            'index'
        ])->name('stock.index');

        Route::get('/stock/create', [
            MealStockTransactionController::class,
            'create'
        ])->name('stock.create');

        Route::post('/stock', [
            MealStockTransactionController::class,
            'store'
        ])->name('stock.store');
    });


/*
|--------------------------------------------------------------------------
| MEAL MAIN MENU
|--------------------------------------------------------------------------
*/

Route::get('/admin/meals', function () {
    return redirect()->route('admin.meal.items.index');
})->name('admin.meals.index');


/*
|--------------------------------------------------------------------------
| MEAL STOCK LOGS
|--------------------------------------------------------------------------
*/

Route::prefix('admin/meal/logs')
    ->name('admin.meal.logs.')
    ->group(function () {

        Route::get('/', [
            MealStockLogController::class,
            'index'
        ])->name('index');

        Route::get('/meal-pdf-test', function () {
            return 'MEAL PDF TEST WORKS';
        })->name('test-pdf');

        Route::get('/download-pdf', [
            MealStockLogController::class,
            'downloadPdf'
        ])->name('pdf');

        Route::get('/excel', [
            MealStockLogController::class,
            'downloadExcel'
        ])->name('excel');

        Route::get('/{mealStockLog}', [
            MealStockLogController::class,
            'show'
        ])->name('show');
    });
