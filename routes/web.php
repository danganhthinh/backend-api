<?php

use App\Consts;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\IllustrationsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\VideoController;
use \App\Http\Controllers\Admin\LearningAnalysisController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', [\App\Http\Controllers\TestController::class, 'index']);
Route::get('/question-furigana', [App\Http\Controllers\Api\QuestionController::class, 'getQuestionFurigana']);
Route::post('/update-question-furigana', [App\Http\Controllers\Api\QuestionController::class, 'updateFurigana']);

Route::any('/', [AuthController::class, 'index']);
Route::get('/switcher_Language/{locale}', [AuthController::class, 'switchLanguage']);
Route::group(['prefix' => 'admin'], function () {
    Route::any('login', [AuthController::class, 'index'])->name('login');
    Route::post('doLogin', [AuthController::class, 'doLogin']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
Route::group(['middleware' => ['auth', 'revalidate'], 'prefix' => 'admin'], function () {
    Route::post('/import-user-excel', [AccountController::class, 'importData'])->name('user.import');
    Route::post('/import-question-excel', [QuestionController::class, 'importData'])->name('question.import');
    Route::get('/change-password', function () {
        return view('admin.users.changePassword');
    });
    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/get-School-Group', [NotificationController::class, 'getSchoolGroup']);
        Route::post('/send-notification', [NotificationController::class, 'notification'])->name('notification');
    });
    Route::group(['prefix' => 'school'], function () {
        Route::get('/list', [SchoolController::class, 'list']);
        Route::get('/search', [SchoolController::class, 'search']);
        Route::post('/search-list', [SchoolController::class, 'searchList']);
        Route::get('/render', [SchoolController::class, 'render_data']);
        Route::get('/list-by-school-year/{id}', [SchoolController::class, 'listBySchoolYear']);
    });
    Route::group(['prefix' => 'group'], function () {
        Route::get('/list', [GroupController::class, 'list']);
        Route::get('/search', [GroupController::class, 'search']);
        Route::get('/render', [GroupController::class, 'render_data']);
    });
    Route::group(['prefix' => 'grade'], function () {
        Route::get('/search', [GradeController::class, 'search']);
        Route::post('/search-list', [GradeController::class, 'searchList']);
        Route::get('/list-by-school/{id}', [GradeController::class, 'listBySchool']);
        Route::get('/grade-by-school/{id}', [GradeController::class, 'infoGradeBySchool']);
        Route::get('/grade-can-delete/{id}', [GradeController::class, 'gradeCanDelete']);
    });
    Route::group(['prefix' => 'user'], function () {
        Route::get('/search', [AccountController::class, 'search']);
        Route::get('/render', [AccountController::class, 'render_data']);
        Route::get('/change-status/{id}', [AccountController::class, 'changeStatus']);
        Route::post('/change-password', [AccountController::class, 'ChangePassword']);
        Route::post('/change-student-password/{id}', [AccountController::class, 'ChangeStudentPassword']);
        Route::get('/show-password/{id}', [AccountController::class, 'showPassword']);
    });
    Route::group(['prefix' => 'mentor'], function () {
        Route::get('/institution-by-mentor/{id}', [AdminAccountController::class, 'institutionByMentor']);
        Route::get('/search', [AdminAccountController::class, 'search']);
        Route::get('/render', [AdminAccountController::class, 'render_data']);
    });
    Route::group(['prefix' => 'video'], function () {
        Route::get('/list-by-subject/{id}', [VideoController::class, 'listBySubject']);
        Route::get('/search', [VideoController::class, 'search']);
        Route::get('/fetch', [VideoController::class, 'fetch']);
        Route::post('/update', [VideoController::class, 'update']);
    });
    Route::group(['prefix' => 'question'], function () {
        Route::get('/search', [QuestionController::class, 'search']);
        Route::group(['prefix' => 'question-image'], function () {
            Route::get('/', [QuestionController::class, 'imageQuestion']);
        });
        Route::group(['prefix' => 'question-text'], function () {
            Route::get('/', [QuestionController::class, 'textQuestion']);
        });
        Route::group(['prefix' => 'question-2D'], function () {
            Route::get('/', [QuestionController::class, 'Question2D']);
        });
        Route::group(['prefix' => 'question-360'], function () {
            Route::get('/', [QuestionController::class, 'Question360']);
        });
        Route::get('/fetch', [QuestionController::class, 'fetch']);
        Route::post('/update', [QuestionController::class, 'update']);
        Route::post('/storeMultipleFile', [QuestionController::class, 'storeMultipleFile']);
        Route::post('/destroyMultipleFile', [QuestionController::class, 'destroyMultipleFile']);
    });
    Route::group(['prefix' => 'illustration'], function () {
        Route::post('/update', [IllustrationsController::class, 'update']);
        Route::get('/fetch', [IllustrationsController::class, 'fetch']);
    });
    Route::group(['prefix' => 'learning'], function () {
        Route::get('/', [LearningAnalysisController::class, 'index'])->name('admin.learning.show');
        Route::get('/account/{id}/{year}', [LearningAnalysisController::class, 'subjectScoreByAccount']);
        Route::get('/account/{id}/{year}', [LearningAnalysisController::class, 'subjectScoreByAccount']);
        Route::get('/grade/{school_id}', [LearningAnalysisController::class, 'gradeBySchoolId']);
        Route::get('/search', [LearningAnalysisController::class, 'search'])->name('admin.learning.search');
        Route::get('/detail/{account_id}/{year}', [LearningAnalysisController::class, 'detailLearning']);
        Route::get('/comparison-month/{account_id}/{year}', [LearningAnalysisController::class, 'comparisonMonth']);
        Route::view('/compare', 'admin.personal-learning.compare-months.index');
    });

    Route::Resources([
        'school' => SchoolController::class,
        'group' => GroupController::class,
        'grade' => GradeController::class,
        'user' => AccountController::class,
        'subject' => SubjectController::class,
        'video' => VideoController::class,
        'question' => QuestionController::class,
        'illustration' => IllustrationsController::class,
        'mentor' => AdminAccountController::class,
    ]);
});
