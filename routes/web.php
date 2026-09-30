<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN AUTH CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;


/*
|--------------------------------------------------------------------------
| ADMIN CORE CONTROLLERS
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


use App\Http\Controllers\PublicResultController;

/*
|--------------------------------------------------------------------------
| SPORTS MANAGEMENT
|--------------------------------------------------------------------------
*/

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
            | RESULTS
            |--------------------------------------------------------------------------
            */

            Route::prefix('results')
                ->name('results.')
                ->group(function () {

                    Route::get('/', [ResultController::class, 'index'])->name('index');

                    Route::get('/marks', [ResultController::class, 'marks'])->name('marks');
                    Route::post('/save-marks', [ResultController::class, 'saveMarks'])->name('save-marks');

                    Route::get('/load-subjects', [ResultController::class, 'loadSubjects'])->name('load-subjects');
                    Route::get('/load-students', [ResultController::class, 'loadStudents'])->name('load-students');
                    Route::get('/load-classes', [ResultController::class, 'loadClasses'])->name('load-classes');
                    Route::get('/exam-classes', [ResultController::class, 'getExamClasses'])->name('exam-classes');

                    Route::get('/generate', [ResultController::class, 'generate'])->name('generate');
                    Route::post('/generate-result', [ResultController::class, 'generateResult'])->name('generate-result');
                    Route::get('/class-results', [ResultController::class, 'classResults'])->name('class-results');

                    Route::post('/verify-class', [ResultController::class, 'verifyClassResults'])->name('verify-class');
                    Route::post('/approve-class', [ResultController::class, 'approveClassResults'])->name('approve-class');
                    Route::post('/publish-class', [ResultController::class, 'publishClassResults'])->name('publish-class');

                    Route::get('/bulk-whatsapp', [ResultController::class, 'bulkWhatsapp'])->name('bulk-whatsapp');

                    Route::get('/history/{result}', [ResultController::class, 'history'])->name('history');
                    Route::get('/history/{result}/version/{version}', [ResultController::class, 'historyVersion'])->name('history-version');

                    Route::get('/{result}/whatsapp', [ResultController::class, 'whatsapp'])->name('whatsapp');
                    Route::get('/{result}/print', [ResultController::class, 'print'])->name('print');
                    Route::get('/{result}/pdf', [ResultController::class, 'pdf'])->name('pdf');

                    Route::post('/{result}/verify', [ResultController::class, 'verify'])->name('verify');
                    Route::post('/{result}/approve', [ResultController::class, 'approve'])->name('approve');
                    Route::post('/{result}/publish', [ResultController::class, 'publish'])->name('publish');

                    Route::get('/{result}', [ResultController::class, 'show'])->name('show');
                });
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
                'timetable',
                TimetableController::class
            )->except(['show']);

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
            | ATTENDANCE
            |--------------------------------------------------------------------------
            */

            Route::get('/attendance', [
                AttendanceController::class,
                'index'
            ])->name('attendance.index');

            Route::get('/attendance/students', [
                AttendanceController::class,
                'students'
            ])->name('attendance.students');

            Route::post('/attendance', [
                AttendanceController::class,
                'store'
            ])->name('attendance.store');

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
| SCHOOL LEAVING CERTIFICATE
|--------------------------------------------------------------------------
*/
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

Route::get('/school-leaving-certificate', [SchoolLeavingCertificateController::class, 'index'])
    ->name('school-leaving-certificate.index');

Route::get('/school-leaving-certificate/create', [SchoolLeavingCertificateController::class, 'create'])
    ->name('school-leaving-certificate.create');

Route::post('/school-leaving-certificate', [SchoolLeavingCertificateController::class, 'store'])
    ->name('school-leaving-certificate.store');

Route::get('/school-leaving-certificate/{schoolLeavingCertificate}/edit', [SchoolLeavingCertificateController::class, 'edit'])
    ->name('school-leaving-certificate.edit');

Route::get('/school-leaving-certificate/{schoolLeavingCertificate}', [SchoolLeavingCertificateController::class, 'show'])
    ->name('school-leaving-certificate.show');

Route::put('/school-leaving-certificate/{schoolLeavingCertificate}', [SchoolLeavingCertificateController::class, 'update'])
    ->name('school-leaving-certificate.update');

Route::delete('/school-leaving-certificate/{schoolLeavingCertificate}', [SchoolLeavingCertificateController::class, 'destroy'])
    ->name('school-leaving-certificate.destroy');

Route::get('/school-leaving-certificate/{schoolLeavingCertificate}/print', [SchoolLeavingCertificateController::class, 'print'])
    ->name('school-leaving-certificate.print');

    /*
|--------------------------------------------------------------------------
| CASTE REPORT
|--------------------------------------------------------------------------
*/

Route::get('/caste-report', [CasteReportController::class, 'index'])
    ->name('caste-report.index');

Route::get('/caste-report/classwise-print', [CasteReportController::class, 'classwisePrint'])
    ->name('caste-report.classwise-print');

    /*
|--------------------------------------------------------------------------
| AGE REPORT
|--------------------------------------------------------------------------
*/

Route::get('/age-report', [AgeReportController::class, 'index'])
    ->name('age-report.index');

Route::get('/age-report/print', [AgeReportController::class, 'print'])
    ->name('age-report.print');
            

            /*
            |--------------------------------------------------------------------------
            | STUDENT SUPPLY KIT CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'student-supply-kits',
                StudentSupplyKitController::class
            )->names('student-supply-kits');


            Route::get('/id-cards/search', [IdCardController::class, 'search'])
                ->name('id-card.search');

            Route::post('/id-cards', [IdCardController::class, 'store'])
                ->name('id-card.store');

            Route::get('/id-cards/{id}/print', [IdCardController::class, 'print'])
                ->name('id-card.print');

            Route::get('/id-cards/{id}', [IdCardController::class, 'show'])
                ->name('id-card.show');

            Route::delete('/id-cards/{id}', [IdCardController::class, 'destroy'])
                ->name('id-card.destroy');

            Route::get('/student-profile', [StudentProfileController::class, 'index'])
                ->name('student-profile.index');

            Route::get('/student-profile/search', [StudentProfileController::class, 'search'])
                ->name('student-profile.search');

            Route::get('/student-documents', [StudentDocumentsController::class, 'index'])
                ->name('student-documents.index');

            Route::get('/student-documents/{student}', [StudentDocumentsController::class, 'show'])
                ->name('student-documents.show');
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

                /*
|--------------------------------------------------------------------------
| ID CARD TEMPLATES
|--------------------------------------------------------------------------
*/

Route::get('/id-card-templates', [IdCardTemplateController::class, 'index'])
    ->name('id-card.templates.index');

Route::get('/id-card-templates/create', [IdCardTemplateController::class, 'create'])
    ->name('id-card.templates.create');

Route::post('/id-card-templates', [IdCardTemplateController::class, 'store'])
    ->name('id-card.templates.store');

Route::get('/id-card-templates/{template}/edit', [IdCardTemplateController::class, 'edit'])
    ->name('id-card.templates.edit');

Route::put('/id-card-templates/{template}', [IdCardTemplateController::class, 'update'])
    ->name('id-card.templates.update');

Route::post('/id-card-templates/{template}/analyze', [IdCardTemplateController::class, 'analyze'])
    ->name('id-card.templates.analyze');

Route::get('/id-card-templates/{template}/analysis', [IdCardTemplateController::class, 'analysis'])
    ->name('id-card.templates.analysis');

Route::post('/id-card-templates/{template}/positions', [IdCardTemplateController::class, 'savePositions'])
    ->name('id-card.templates.save-positions');

Route::post('/id-card-templates/{template}/toggle-status', [IdCardTemplateController::class, 'toggleStatus'])
    ->name('id-card.templates.toggle-status');

Route::delete('/id-card-templates/{template}', [IdCardTemplateController::class, 'destroy'])
    ->name('id-card.templates.destroy');

            /* LOCATION API */

            Route::get('/locations/states', [LocationController::class, 'states'])
                ->name('locations.states');

            Route::get('/locations/districts', [LocationController::class, 'districts'])
                ->name('locations.districts');

                Route::get('/locations/tehsils', [LocationController::class, 'tehsils'])
    ->name('locations.tehsils');

    Route::get('/locations/locations', [LocationController::class, 'locations'])
    ->name('locations.locations');

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
            | EXAM CRUD
            |--------------------------------------------------------------------------
            */

            Route::get('/sports', function () {
                return 'Sports Management';
            })->name('sports.index');
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

                    Route::get(
                        '/',
                        [
                            ExamClassController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::post(
                        '/',
                        [
                            ExamClassController::class,
                            'store'
                        ]
                    )->name('store');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM SUBJECTS
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/subjects')
                ->name('exam-subjects.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ExamSubjectController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::post(
                        '/',
                        [
                            ExamSubjectController::class,
                            'store'
                        ]
                    )->name('store');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM SESSIONS
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/sessions')
                ->name('exam-sessions.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ExamSessionController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ExamSessionController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ExamSessionController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{session}/edit',
                        [
                            ExamSessionController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{session}',
                        [
                            ExamSessionController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::delete(
                        '/{session}',
                        [
                            ExamSessionController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM HOLIDAYS
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/holidays')
                ->name('exam-holidays.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ExamHolidayController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ExamHolidayController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ExamHolidayController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{holiday}/edit',
                        [
                            ExamHolidayController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{holiday}',
                        [
                            ExamHolidayController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::delete(
                        '/{holiday}',
                        [
                            ExamHolidayController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | GENERATE TIMETABLE FORM
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule/generate',
                [
                    ExamScheduleController::class,
                    'generateForm'
                ]
            )->name('exam-schedules.generate.form');


            /*
            |--------------------------------------------------------------------------
            | GENERATE TIMETABLE
            |--------------------------------------------------------------------------
            */

            Route::post(
                'exams/{exam}/schedule/generate',
                [
                    ExamScheduleController::class,
                    'generate'
                ]
            )->name('exam-schedules.generate');


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
                    | TRANSPORT RECORDS
                    |--------------------------------------------------------------------------
                    */


                    /*
                    |--------------------------------------------------------------------------
                    | FETCH STUDENTS BY CLASS
                    |--------------------------------------------------------------------------
                    |
                    | Students are loaded only after a class is selected.
                    |
                    */

                    Route::get(
                        'records/students-by-class',
                        [
                            \App\Http\Controllers\Transport\TransportRecordController::class,
                            'studentsByClass'
                        ]
                    )->name('records.students-by-class');


                    /*
                    |--------------------------------------------------------------------------
                    | DOWNLOAD TRANSPORT RECORD PDF
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'records/{transportRecord}/download-pdf',
                        [
                            \App\Http\Controllers\Transport\TransportRecordController::class,
                            'downloadPdf'
                        ]
                    )->name('records.download-pdf');


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT RECORD RESOURCE ROUTES
                    |--------------------------------------------------------------------------
                    */

                    Route::resource(
                        'records',
                        \App\Http\Controllers\Transport\TransportRecordController::class
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
                        \App\Http\Controllers\Transport\TransportRouteController::class
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
                        \App\Http\Controllers\Transport\TransportVehicleController::class
                    )->parameters([
                        'vehicles' => 'transportVehicle',
                    ]);

                });


            /*
            |--------------------------------------------------------------------------
            | MEAL MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::prefix('meal')
                ->name('meal.')
                ->group(function () {

                    Route::get('/items', [
                        MealItemController::class,
                        'index'
                    ])->name('items.index');

                    Route::get('/items/create', [
                        MealItemController::class,
                        'create'
                    ])->name('items.create');

                    Route::post('/items', [
                        MealItemController::class,
                        'store'
                    ])->name('items.store');

                    Route::get('/items/{mealItem}/edit', [
                        MealItemController::class,
                        'edit'
                    ])->name('items.edit');

                    Route::put('/items/{mealItem}', [
                        MealItemController::class,
                        'update'
                    ])->name('items.update');

                    Route::delete('/items/{mealItem}', [
                        MealItemController::class,
                        'destroy'
                    ])->name('items.destroy');


                    /*
                    |--------------------------------------------------------------------------
                    | MEAL STOCK TRANSACTIONS
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/items/stock', [
                        MealStockTransactionController::class,
                        'index'
                    ])->name('items.stock.index');

                    Route::get('/items/stock/create', [
                        MealStockTransactionController::class,
                        'create'
                    ])->name('items.stock.create');

                    Route::post('/items/stock', [
                        MealStockTransactionController::class,
                        'store'
                    ])->name('items.stock.store');


                    /*
                    |--------------------------------------------------------------------------
                    | MEAL LOGS
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/logs', [
                        MealStockLogController::class,
                        'index'
                    ])->name('logs.index');

                    Route::get('/logs/{mealStockLog}', [
                        MealStockLogController::class,
                        'show'
                    ])->name('logs.show');
                });


            /*
            |--------------------------------------------------------------------------
            | SPORTS - GAMES / EVENTS
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



            //REPORTS

            Route::get('/admin/reports', [ReportsController::class, 'index'])
    ->name('reports.index');

    //ALL REPORTS
        // Notice
        Route::get('/notices', function () {
            return 'Notice Management';
        })->name('notices.index');

Route::get('/admin/reports', [ReportsController::class, 'index'])
    ->name('reports.index');

Route::get('/reports/students', [StudentReportController::class, 'index'])
    ->name('reports.students');

    Route::get('/reports/students/pdf', [StudentReportController::class, 'pdf'])
    ->name('reports.students.pdf');

    Route::get('/reports/students/excel', function (\Illuminate\Http\Request $request) {

    $filters = $request->only([
        'search',
        'academic_year',
        'class',
        'section',
        'gender',
        'status',
    ]);

    return Excel::download(
        new StudentReportExport($filters),
        'student-report.xlsx'
    );

})->name('reports.students.excel');

            /* CLASS / SUBJECT MANAGEMENT */

            Route::resource('classes', SchoolClassController::class)
                ->names('classes');

            Route::resource('subjects', SubjectController::class)
                ->names('subjects');

            /* LOGOUT */
            /*
            |--------------------------------------------------------------------------
            | VIEW TIMETABLE
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule',
                [
                    ExamScheduleController::class,
                    'index'
                ]
            )->name('exam-schedules.index');


            /*
            |--------------------------------------------------------------------------
            | PRINT TIMETABLE
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule/print',
                [
                    ExamScheduleController::class,
                    'print'
                ]
            )->name('exam-schedules.print');


            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD TIMETABLE PDF
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule/pdf',
                [
                    ExamScheduleController::class,
                    'pdf'
                ]
            )->name('exam-schedules.pdf');


            /*
            |--------------------------------------------------------------------------
            | EDIT TIMETABLE ENTRY
            |--------------------------------------------------------------------------
            */

            Route::get(
                'exams/{exam}/schedule/{schedule}/edit',
                [
                    ExamScheduleController::class,
                    'edit'
                ]
            )->name('exam-schedules.edit');


            /*
            |--------------------------------------------------------------------------
            | UPDATE TIMETABLE ENTRY
            |--------------------------------------------------------------------------
            */

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

Route::get('/reports/transport/pdf', [TransportReportController::class, 'pdf'])
    ->name('reports.transport.pdf');

Route::get('/reports/transport/excel', [TransportReportController::class, 'excel'])
    ->name('reports.transport.excel');




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
Route::get(
    '/result',
    [PublicResultController::class, 'index']
)->name('result.public');

Route::post(
    '/result/search',
    [PublicResultController::class, 'search']
)->name('result.search');

Route::get(
    '/result/show',
    [PublicResultController::class, 'show']
)->name('result.public.show');

Route::get(
    '/result/{student}/pdf',
    [PublicResultController::class, 'pdf']
)->name('result.pdf');

Route::get(
    '/result/{student}/pdf/download',
    [PublicResultController::class, 'downloadPdf']
)->name('result.pdf.download');


Route::get('/result/captcha', [PublicResultController::class, 'refreshCaptcha'])
    ->name('result.captcha');
