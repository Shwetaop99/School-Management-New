<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sports Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Sports\GameController;
use App\Http\Controllers\Sports\AchievementController;
use App\Http\Controllers\Sports\EquipmentController;


/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;

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
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\StaffCategoryController;
use App\Http\Controllers\Admin\LeaveApplicationController;
use App\Http\Controllers\Admin\ClassTeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Admin\StudentDocumentsController;
use App\Http\Controllers\Admin\StudentGeneralRegisterController;
use App\Http\Controllers\Admin\StudentHealthController;
use App\Http\Controllers\Admin\BonafideCertificateController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\IdCardController;
use App\Http\Controllers\Admin\StudentSupplyKitController;
use App\Http\Controllers\Admin\SchoolLeavingCertificateController;
use App\Http\Controllers\Admin\CasteReportController;
use App\Http\Controllers\Admin\AgeReportController;
use App\Http\Controllers\Admin\IdCardTemplateController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\StaffReportController;
use App\Http\Controllers\Admin\AttendanceReportController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\SchoolSettingController;
use App\Http\Controllers\Admin\SupplyItemController;
use App\Http\Controllers\Admin\KitTemplateController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamClassController;
use App\Http\Controllers\Admin\ExamSubjectController;
use App\Http\Controllers\Admin\ExamScheduleController;
use App\Http\Controllers\Admin\ExamSessionController;
use App\Http\Controllers\Admin\ExamHolidayController;
use App\Http\Controllers\Admin\StudentReportController;
use App\Http\Controllers\Admin\MealReportController;
use App\Http\Controllers\Admin\TransportReportController;


/*
|--------------------------------------------------------------------------
| EXPORTS / EXCEL
|--------------------------------------------------------------------------
*/

use App\Exports\StudentReportExport;
use Maatwebsite\Excel\Facades\Excel;


/*
|--------------------------------------------------------------------------
| CLASS CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Class\SchoolClassController;
use App\Http\Controllers\Class\SubjectController;


/*
|--------------------------------------------------------------------------
| MEAL MANAGEMENT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Meal\MealItemController;
use App\Http\Controllers\Meal\MealStockLogController;
use App\Http\Controllers\Meal\MealStockTransactionController;


/*
|--------------------------------------------------------------------------
| SPORTS MANAGEMENT
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Class\SchoolClassController;
use App\Http\Controllers\Class\SubjectController;

use App\Http\Controllers\Sports\SportsController;
use App\Http\Controllers\Sports\AchievementController;
use App\Http\Controllers\Sports\EquipmentController;
use App\Http\Controllers\Sports\GameController;


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
        | SPORTS MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::prefix('sports')
            ->name('sports.')
            ->group(function () {

                Route::get('/', [
                    SportsController::class,
                    'index'
                ])->name('index');

                Route::resource(
                    'games',
                    GameController::class
                );

                Route::resource(
                    'achievements',
                    AchievementController::class
                );

                Route::resource(
                    'equipment',
                    EquipmentController::class
                );
            });


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED ADMIN AREA
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
            | REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/reports', [
                ReportsController::class,
                'index'
            ])->name('reports.index');


            /*
            |--------------------------------------------------------------------------
            | LOCATION API
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
            | STUDENT AJAX ROUTES
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


            /*
            |--------------------------------------------------------------------------
            | STUDENT CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'students',
                StudentController::class
            );


            /*
            |--------------------------------------------------------------------------
            | STUDENT PROFILE
            |--------------------------------------------------------------------------
            */

            Route::get('/student-profile', [
                StudentProfileController::class,
                'index'
            ])->name('student-profile.index');

            Route::get('/student-profile/search', [
                StudentProfileController::class,
                'search'
            ])->name('student-profile.search');


            /*
            |--------------------------------------------------------------------------
            | STUDENT DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get('/student-documents', [
                StudentDocumentsController::class,
                'index'
            ])->name('student-documents.index');


            /*
            |--------------------------------------------------------------------------
            | STUDENT HEALTH
            |--------------------------------------------------------------------------
            */

            Route::get('/student-health', [
                StudentHealthController::class,
                'index'
            ])->name('student-health.index');

            Route::get('/student-health/create/{student}', [
                StudentHealthController::class,
                'create'
            ])->name('student-health.create');

            Route::post('/student-health/store/{student}', [
                StudentHealthController::class,
                'store'
            ])->name('student-health.store');

            Route::get('/student-health/{healthRecord}/print', [
                StudentHealthController::class,
                'print'
            ])->name('student-health.print');

            Route::get('/student-health/{healthRecord}/edit', [
                StudentHealthController::class,
                'edit'
            ])->name('student-health.edit');

            Route::get('/student-health/{healthRecord}', [
                StudentHealthController::class,
                'show'
            ])->name('student-health.show');

            Route::put('/student-health/{healthRecord}', [
                StudentHealthController::class,
                'update'
            ])->name('student-health.update');

            Route::delete('/student-health/{healthRecord}', [
                StudentHealthController::class,
                'destroy'
            ])->name('student-health.destroy');


            /*
            |--------------------------------------------------------------------------
            | STUDENT GENERAL REGISTER
            |--------------------------------------------------------------------------
            */

            Route::get('/student-general-register', [
                StudentGeneralRegisterController::class,
                'index'
            ])->name('student-general-register.index');

            Route::get('/student-general-register/print', [
                StudentGeneralRegisterController::class,
                'print'
            ])->name('student-general-register.print');

            Route::get('/student-general-register/{student}', [
                StudentGeneralRegisterController::class,
                'show'
            ])->name('student-general-register.show');


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

            Route::prefix('bonafide-certificate')
                ->name('bonafide.')
                ->group(function () {

                    Route::get('/', [
                        BonafideCertificateController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        BonafideCertificateController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        BonafideCertificateController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{bonafide}/edit', [
                        BonafideCertificateController::class,
                        'edit'
                    ])->name('edit');

                    Route::get('/{bonafide}', [
                        BonafideCertificateController::class,
                        'show'
                    ])->name('show');

                    Route::put('/{bonafide}', [
                        BonafideCertificateController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{bonafide}', [
                        BonafideCertificateController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | SCHOOL LEAVING CERTIFICATE
            |--------------------------------------------------------------------------
            */

            Route::prefix('school-leaving-certificate')
                ->name('school-leaving-certificate.')
                ->group(function () {

                    Route::get('/', [
                        SchoolLeavingCertificateController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        SchoolLeavingCertificateController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        SchoolLeavingCertificateController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{schoolLeavingCertificate}/print', [
                        SchoolLeavingCertificateController::class,
                        'print'
                    ])->name('print');

                    Route::get('/{schoolLeavingCertificate}/edit', [
                        SchoolLeavingCertificateController::class,
                        'edit'
                    ])->name('edit');

                    Route::get('/{schoolLeavingCertificate}', [
                        SchoolLeavingCertificateController::class,
                        'show'
                    ])->name('show');

                    Route::put('/{schoolLeavingCertificate}', [
                        SchoolLeavingCertificateController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{schoolLeavingCertificate}', [
                        SchoolLeavingCertificateController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | ID CARD MANAGEMENT
            |--------------------------------------------------------------------------
            */

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


                    /*
                    | Templates
                    */

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


                    /*
                    | ID Card specific routes
                    */

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
            | TEACHER ALLOCATION
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
            | TEACHERS
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'teachers',
                TeacherController::class
            )->whereNumber('teacher');


            /*
            |--------------------------------------------------------------------------
            | OTHER STAFF MANAGEMENT
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
            | TEACHER SALARY / PAYROLL
            |--------------------------------------------------------------------------
            */

            Route::prefix('salary')
                ->name('salary.')
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
            | STUDENT ID CARD
            |--------------------------------------------------------------------------
            */

            Route::get('/students/id-card', [
                IdCardController::class,
                'index'
            ])->name('students.id-card');

            Route::resource(
                'id-card/templates',
                IdCardTemplateController::class
            )->names('id-card.templates');

            Route::get('/id-card', [
                IdCardController::class,
                'index'
            ])->name('id-card.index');

            Route::get('/id-card/create', [
                IdCardController::class,
                'create'
            ])->name('id-card.create');

            Route::post('/id-card', [
                IdCardController::class,
                'store'
            ])->name('id-card.store');

            Route::get('/id-card/{id}', [
                IdCardController::class,
                'show'
            ])->name('id-card.show');

            Route::get('/id-card/{id}/print', [
                IdCardController::class,
                'print'
            ])->name('id-card.print');

            Route::delete('/id-card/{id}', [
                IdCardController::class,
                'destroy'
            ])->name('id-card.destroy');


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
            | TEACHER TIMETABLE
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
            ])
                ->name('library.books.show')
                ->middleware('permission:library.view');


            /*
            |--------------------------------------------------------------------------
            | CASTE REPORT
            |--------------------------------------------------------------------------
            */

            Route::get('/caste-report', [
                CasteReportController::class,
                'index'
            ])->name('caste-report.index');

            Route::get('/caste-report/class-wise-print', [
                CasteReportController::class,
                'classWisePrint'
            ])->name('caste-report.class-wise-print');


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

            Route::get('/attendance/students', [
                AttendanceController::class,
                'students'
            ])->name('attendance.students');


            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE - SECTIONS
            |--------------------------------------------------------------------------
            */

            Route::get('/attendance/sections', [
                AttendanceController::class,
                'sections'
            ])->name('attendance.sections');


            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE - SAVE
            |--------------------------------------------------------------------------
            */

            Route::post('/attendance/students', [
                AttendanceController::class,
                'store'
            ])->name('attendance.students.store');

            Route::post('/attendance/store', [
                AttendanceController::class,
                'store'
            ])->name('attendance.store');


            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE - PDF
            |--------------------------------------------------------------------------
            */

            Route::get('/attendance/pdf', [
                AttendanceController::class,
                'pdf'
            ])->name('attendance.pdf');


            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE - EXCEL
            |--------------------------------------------------------------------------
            */

            Route::get('/attendance/excel', [
                AttendanceController::class,
                'excel'
            ])
                ->name('library.reports.excel')
                ->middleware('permission:library.reports');

                

            /* OTHER STAFF */

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
            | STUDENT SUPPLY KIT - AJAX
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
            | STUDENT SUPPLY KIT - PRINT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/student-supply-kits/{studentSupplyKit}/print',
                [
                    StudentSupplyKitController::class,
                    'print'
                ]
            )->name('student-supply-kits.print');

Route::get('/students/create', [StudentController::class, 'create'])
    ->name('students.create');

Route::post('/students', [StudentController::class, 'store'])
    ->name('students.store');

Route::get('/students/next-roll-number', [StudentController::class, 'nextRollNumber'])
    ->name('students.next-roll-number');    

Route::get('/students/{student}', [StudentController::class, 'show'])
    ->name('students.show');

Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
    ->name('students.edit');

Route::put('/students/{student}', [StudentController::class, 'update'])
    ->name('students.update');

Route::delete('/students/{student}', [StudentController::class, 'destroy'])
    ->name('students.destroy');

    /*
|--------------------------------------------------------------------------
| STUDENT SUPPLY KITS
|--------------------------------------------------------------------------
*/

Route::get('/student-supply-kits', [StudentSupplyKitController::class, 'index'])
    ->name('student-supply-kits.index');

Route::get('/student-supply-kits/create', [StudentSupplyKitController::class, 'create'])
    ->name('student-supply-kits.create');
    

Route::post('/student-supply-kits', [StudentSupplyKitController::class, 'store'])
    ->name('student-supply-kits.store');

Route::get('/student-supply-kits/search-students', [StudentSupplyKitController::class, 'searchStudents'])
    ->name('student-supply-kits.search-students');

Route::get('/student-supply-kits/template-items', [StudentSupplyKitController::class, 'templateItems'])
    ->name('student-supply-kits.template-items');

Route::get('/student-supply-kits/supply-items', [StudentSupplyKitController::class, 'supplyItems'])
    ->name('student-supply-kits.supply-items');

Route::get('/student-supply-kits/{studentSupplyKit}/print', [StudentSupplyKitController::class, 'print'])
    ->name('student-supply-kits.print');

Route::get('/student-supply-kits/{studentSupplyKit}/edit', [StudentSupplyKitController::class, 'edit'])
    ->name('student-supply-kits.edit');

Route::get('/student-supply-kits/{studentSupplyKit}', [StudentSupplyKitController::class, 'show'])
    ->name('student-supply-kits.show');

Route::put('/student-supply-kits/{studentSupplyKit}', [StudentSupplyKitController::class, 'update'])
    ->name('student-supply-kits.update');

Route::delete('/student-supply-kits/{studentSupplyKit}', [StudentSupplyKitController::class, 'destroy'])
    ->name('student-supply-kits.destroy');


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
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', [
            LoginController::class,
            'logout'
        ])->middleware('auth')->name('logout');
    });

    Route::get('/reports/staff/pdf', [StaffReportController::class, 'pdf'])
    ->name('reports.staff.pdf');

Route::get('/reports/staff/excel', [StaffReportController::class, 'excel'])
    ->name('reports.staff.excel');

Route::get('/reports/staff/{otherStaff}', [StaffReportController::class, 'show'])
    ->name('reports.staff.show');

    Route::get('/reports/staff', [StaffReportController::class, 'index'])
    ->name('reports.staff');

Route::get('/reports/staff/{otherStaff}', [StaffReportController::class, 'show'])
    ->name('reports.staff.show');

    Route::get('/reports/attendance', [AttendanceReportController::class, 'index'])
    ->name('reports.attendance');

    Route::get('/reports/attendance/pdf', [AttendanceReportController::class, 'pdf'])
    ->name('reports.attendance.pdf');

Route::get('/reports/attendance/excel', [AttendanceReportController::class, 'excel'])
    ->name('reports.attendance.excel');

    Route::get('/reports/meal', [MealReportController::class, 'index'])
    ->name('reports.meal');

    Route::get('/reports/meal', [MealReportController::class, 'index'])
    ->name('reports.meal');

Route::get('/reports/meal/pdf', [MealReportController::class, 'pdf'])
    ->name('reports.meal.pdf');

Route::get('/reports/meal/excel', [MealReportController::class, 'excel'])
    ->name('reports.meal.excel');

    Route::get('/reports/transport', [TransportReportController::class, 'index'])
    ->name('reports.transport');

    Route::get('/reports/transport/student-search', [
    TransportReportController::class,
    'studentSearch'
])->name('reports.transport.student-search');

Route::get('/reports/transport/pdf', [TransportReportController::class, 'pdf'])
    ->name('reports.transport.pdf');

Route::get('/reports/transport/excel', [TransportReportController::class, 'excel'])
    ->name('reports.transport.excel');

    Route::get('/reports/transport/student-travel', [
    TransportReportController::class,
    'studentTravel'
])->name('reports.transport.student-travel');

Route::get('/reports/transport/student-travel/pdf', [
    TransportReportController::class,
    'studentTravelPdf'
])->name('reports.transport.student-travel.pdf');

Route::get('/reports/transport/student-travel/excel', [
    TransportReportController::class,
    'studentTravelExcel'
])->name('reports.transport.student-travel.excel');

Route::get('/reports/transport/vehicle', [
    TransportReportController::class,
    'vehicle'
])->name('reports.transport.vehicle');

Route::get('/reports/transport/vehicle/pdf', [
    TransportReportController::class,
    'vehiclePdf'
])->name('reports.transport.vehicle.pdf');

Route::get('/reports/transport/vehicle/excel', [
    TransportReportController::class,
    'vehicleExcel'
])->name('reports.transport.vehicle.excel');




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
| MEAL MANAGEMENT MAIN MENU
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

        /*
        |--------------------------------------------------------------------------
        | LOGS INDEX
        |--------------------------------------------------------------------------
        */

        // Log details
        Route::get('/{mealStockLog}', [MealStockLogController::class, 'show'])
            ->name('show');
    });
   
        Route::get('/', [
            MealStockLogController::class,
            'index'
        ])->name('index');

        /*
        |--------------------------------------------------------------------------
        | PDF TEST ROUTE
        |--------------------------------------------------------------------------
        */

        Route::get('/meal-pdf-test', function () {
            return 'MEAL PDF TEST WORKS';
        })->name('test-pdf');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD MEAL LOGS PDF
        |--------------------------------------------------------------------------
        */

        Route::get('/download-pdf', [
            MealStockLogController::class,
            'downloadPdf'
        ])->name('pdf');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD MEAL LOGS EXCEL
        |--------------------------------------------------------------------------
        */

        Route::get('/excel', [
            MealStockLogController::class,
            'downloadExcel'
        ])->name('excel');


        /*
        |--------------------------------------------------------------------------
        | VIEW MEAL LOG
        |--------------------------------------------------------------------------
        */

        Route::get('/{mealStockLog}', [
            MealStockLogController::class,
            'show'
        ])->name('show');
    });
    });
    