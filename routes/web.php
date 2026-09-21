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

/*
|--------------------------------------------------------------------------
| Class Management
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Class\SchoolClassController;
use App\Http\Controllers\Class\SubjectController;

/*
|--------------------------------------------------------------------------
| Meal Management
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Meal\MealItemController;
use App\Http\Controllers\Meal\MealStockLogController;
use App\Http\Controllers\Meal\MealStockTransactionController;

/*
|--------------------------------------------------------------------------
| Sports Management
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Sports\SportsController;
use App\Http\Controllers\Sports\GameController;
use App\Http\Controllers\Sports\AchievementController;
use App\Http\Controllers\Sports\EquipmentController;


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
            | LIBRARY - LIBRARIAN
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
            | LIBRARY - REPORTS
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
            | CLASS MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'classes',
                SchoolClassController::class
            )->names('classes');


            /*
            |--------------------------------------------------------------------------
            | SUBJECT MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'subjects',
                SubjectController::class
            )->names('subjects');


            /*
            |--------------------------------------------------------------------------
            | MEAL ITEMS / STOCK MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::prefix('meal/items')
                ->name('meal.items.')
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
                    | STOCK IN / OUT
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
            | MEAL STOCK LOGS
            |--------------------------------------------------------------------------
            */

            Route::prefix('meal/logs')
                ->name('meal.logs.')
                ->group(function () {

                    Route::get('/', [
                        MealStockLogController::class,
                        'index'
                    ])->name('index');

                    Route::get('/pdf', [
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

            Route::get('/sports/games/{game}/edit', [
                GameController::class,
                'edit'
            ])->name('sports.games.edit');

            Route::put('/sports/games/{game}', [
                GameController::class,
                'update'
            ])->name('sports.games.update');

            Route::delete('/sports/games/{game}', [
                GameController::class,
                'destroy'
            ])->name('sports.games.destroy');

            Route::get('/sports/games/export', [
                GameController::class,
                'export'
            ])->name('sports.games.export');

            Route::get('/sports/games/{game}', [
                GameController::class,
                'show'
            ])->name('sports.games.show');


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

            Route::put('/sports/achievements/{achievement}', [
                AchievementController::class,
                'update'
            ])->name('sports.achievements.update');

            Route::delete('/sports/achievements/{achievement}', [
                AchievementController::class,
                'destroy'
            ])->name('sports.achievements.destroy');

            Route::get('/sports/achievements/{achievement}', [
                AchievementController::class,
                'show'
            ])->name('sports.achievements.show');


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

            Route::put('/sports/equipment/{equipment}', [
                EquipmentController::class,
                'update'
            ])->name('sports.equipment.update');

            Route::delete('/sports/equipment/{equipment}', [
                EquipmentController::class,
                'destroy'
            ])->name('sports.equipment.destroy');

            Route::get('/sports/equipment/{equipment}', [
                EquipmentController::class,
                'show'
            ])->name('sports.equipment.show');


            /*
            |--------------------------------------------------------------------------
            | MAIN MODULE PAGES
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

            Route::get('/timetable', function () {
                return 'Time Table';
            })->name('timetable.index');

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

            Route::get('/sports', [
                SportsController::class,
                'index'
            ])->name('sports.index');

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