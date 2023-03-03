<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'mobile'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/open-app', [AuthController::class, 'numberOpenApp']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/update-usage-time', [AuthController::class, 'updateUsageTime']);
        Route::post('/update-fcm',[NotificationController::class,'updateToken']);
        Route::group(['prefix' => 'training'], function () {
            Route::get('/get-subjects', [SubjectController::class, 'getSubjectsTrainingByAccount']);
            Route::get('/get-question-by-subject/{subject_id}', [QuestionController::class, 'renderQuestion']);
            Route::get('/questions-random', [QuestionController::class, 'randomQuestion']);
            Route::get('/detail-question/{question_id}', [QuestionController::class, 'detailQuestion']);
            Route::post('/update-progress', [QuestionController::class, 'updateProgress']);
            Route::get('/questions-by-progress/{id}', [QuestionController::class, 'questionsByProgress']);
        });

        Route::group(['prefix' => 'video'], function () {
            Route::get('/get-subjects', [SubjectController::class, 'getSubjectsTrainingByAccount']);
            Route::get('/get-video-by-subject/{subject_id}', [VideoController::class, 'getVideosBySubject']);
            Route::get('/detail-video/{video_id}', [VideoController::class, 'detailVideo']);
            Route::post('/update-progress-video/{id}', [VideoController::class, 'updateProgressVideo']);
        });

        Route::group(['prefix' => 'my-page'], function () {
            Route::get('/get-level-stamp', [AccountController::class, 'getLevelStamp']);
            Route::get('/get-video-stamp', [AccountController::class, 'getVideoStamp']);
            Route::get('/get-subjects-level', [AccountController::class, 'getSubjectsLevel']);
            Route::get('/', [AccountController::class, 'myPage']);
        });
    });
});
