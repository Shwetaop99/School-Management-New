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
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TeacherReportController;
use App\Http\Controllers\Admin\ClassTeacherController;

/*
|--------------------------------------------------------------------------
| STUDENT CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Admin\StudentDocumentsController;
use App\Http\Controllers\Admin\StudentGeneralRegisterController;
use App\Http\Controllers\Admin\StudentHealthController;

/*
|--------------------------------------------------------------------------
| ID CARD CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\IdCardController;
use App\Http\Controllers\Admin\IdCardTemplateController;

/*
|--------------------------------------------------------------------------
| CERTIFICATE CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\BonafideCertificateController;
use App\Http\Controllers\Admin\SchoolLeavingCertificateController;

/*
|--------------------------------------------------------------------------
| LOCATION / REPORT / ATTENDANCE CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CasteReportController;
use App\Http\Controllers\Admin\AgeReportController;

/*
|--------------------------------------------------------------------------
| SCHOOL SETTINGS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\SchoolSettingController;

/*
|--------------------------------------------------------------------------
| SUPPLY KIT CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\SupplyItemController;
use App\Http\Controllers\Admin\KitTemplateController;
use App\Http\Controllers\Admin\StudentSupplyKitController;

/*
|--------------------------------------------------------------------------
| EXAM CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamClassController;
use App\Http\Controllers\Admin\ExamClassSectionController;
use App\Http\Controllers\Admin\ExamScheduleController;
use App\Http\Controllers\Admin\ExamTimetableController;


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
            ])->name('dashboard');


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

                    /*
                    | ID CARD GENERATION
                    */

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


                    /*
                    | ID CARD TEMPLATES
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
            | CLASS TEACHER ASSIGNMENT
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
            | TIMETABLE
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

            Route::get('/fees', function () {
                return 'Fees Management';
            })->name('fees.index');


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

            Route::post(
                'exams/{exam}/classes/add-all',
                [
                    ExamClassController::class,
                    'addAll'
                ]
            )->name('exam-classes.add-all');

            Route::prefix('exams/{exam}/classes')
                ->name('exam-classes.')
                ->group(function () {

                    Route::get('/', [
                        ExamClassController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ExamClassController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ExamClassController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{examClass}/edit', [
                        ExamClassController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{examClass}', [
                        ExamClassController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{examClass}', [
                        ExamClassController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM CLASS SECTIONS
            |--------------------------------------------------------------------------
            */

            Route::post(
                'exams/{exam}/classes/{examClass}/sections/add-all',
                [
                    ExamClassSectionController::class,
                    'addAll'
                ]
            )->name('exam-class-sections.add-all');

            Route::prefix(
                'exams/{exam}/classes/{examClass}/sections'
            )
                ->name('exam-class-sections.')
                ->group(function () {

                    Route::get('/', [
                        ExamClassSectionController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ExamClassSectionController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ExamClassSectionController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{section}/edit', [
                        ExamClassSectionController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{section}', [
                        ExamClassSectionController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{section}', [
                        ExamClassSectionController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM SCHEDULE
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/schedule')
                ->name('exam-schedules.')
                ->group(function () {

                    Route::get('/', [
                        ExamScheduleController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ExamScheduleController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ExamScheduleController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{schedule}/edit', [
                        ExamScheduleController::class,
                        'edit'
                    ])->name('edit');

                    Route::get('/{schedule}/print', [
                        ExamScheduleController::class,
                        'print'
                    ])->name('print');

                    Route::put('/{schedule}', [
                        ExamScheduleController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{schedule}', [
                        ExamScheduleController::class,
                        'destroy'
                    ])->name('destroy');
                });


            /*
            |--------------------------------------------------------------------------
            | EXAM TIMETABLE
            |--------------------------------------------------------------------------
            */

            Route::prefix('exams/{exam}/timetable')
                ->name('exam-timetable.')
                ->group(function () {

                    Route::get('/', [
                        ExamTimetableController::class,
                        'index'
                    ])->name('index');

                    Route::get('/print', [
                        ExamTimetableController::class,
                        'print'
                    ])->name('print');
                });


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
            | NOTICES
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
            | CLASS MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/classes', function () {
                return 'Class Management';
            })->name('classes.index');


            /*
            |--------------------------------------------------------------------------
            | SCHOOL SETTINGS
            |--------------------------------------------------------------------------
            */

            Route::prefix('settings')
                ->name('settings.')
                ->group(function () {

                    Route::get('/', [
                        SchoolSettingController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        SchoolSettingController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        SchoolSettingController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{schoolSetting}', [
                        SchoolSettingController::class,
                        'show'
                    ])->name('show');

                    Route::get('/{schoolSetting}/edit', [
                        SchoolSettingController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{schoolSetting}', [
                        SchoolSettingController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{schoolSetting}', [
                        SchoolSettingController::class,
                        'destroy'
                    ])->name('destroy');
                });
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