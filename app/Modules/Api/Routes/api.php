<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\AuthController;
use Modules\Api\Http\Controllers\AuthSocialController;
use Modules\Api\Http\Controllers\LanguageController;
use Modules\Api\Http\Controllers\LessonController;
use Modules\Api\Http\Controllers\LessonTestController;
use Modules\Api\Http\Controllers\LessonTopicController;
use Modules\Api\Http\Controllers\MukhtasibatController;
use Modules\Api\Http\Controllers\ParishController;
use Modules\Api\Http\Controllers\PasswordResetController;
use Modules\Api\Http\Controllers\PositionController;
use Modules\Api\Http\Controllers\TeacherController;
use Modules\Api\Http\Controllers\UserController;

Route::group(['prefix' => 'api', 'middleware' => ['api', 'apiLanguageSwitcher']], function() {

    Route::get('teachers', [TeacherController::class, 'list']);

    Route::group(['prefix' => 'auth'], function () {
        Route::post('pre-validate', [AuthController::class, 'preRegistrationValidation']);
        Route::post('registration', [AuthController::class, 'registration']);
        Route::get('send-registration-email', [AuthController::class, 'sendRegistrationEmail'])
            ->middleware(['auth:api']);
        Route::post('confirm-email', [AuthController::class, 'confirmEmail']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);

        Route::group(['prefix' => 'social'], function () {
            Route::get('/', [AuthSocialController::class, 'list']);

            Route::match(['post', 'get'],'/{provider}/callback', [AuthSocialController::class, 'callback']);

            Route::post('/{provider}/login', [AuthSocialController::class, 'login'])
                ->middleware(['socMedia.codeFormatter']);;
        });
    });

    Route::group(['prefix' => 'password'], function () {
       Route::post('send-token', [PasswordResetController::class, 'sendToken']);
       Route::post('reset', [PasswordResetController::class, 'reset']);
    });

    Route::group(['prefix' => 'language', 'middleware' => []], function() {
        Route::get('app-languages-list', [LanguageController::class, 'appLanguagesList']);
        Route::get('app-languages-messages/{code}', [LanguageController::class, 'appLanguagesMessages']);
        Route::get('app-languages-agreements/{code}', [LanguageController::class, 'appLanguagesAgreements']);
        Route::get('app-languages-privacy-policy/{code}', [LanguageController::class, 'appLanguagesPrivacyPolicy']);
    });

    Route::post('user/confirm-change-email', [UserController::class, 'confirmChangeEmail'])->name('api.user.confirmChangeEmail');

    Route::group(['middleware' => ['auth:api', 'api.checkUserStatus']], function() {

        Route::group(['prefix' => 'user'], function () {
            Route::get('profile', [UserController::class, 'profile'])->name('api.user.profile');
            Route::post('update', [UserController::class, 'update'])->name('api.user.update');
            Route::post('change-email', [UserController::class, 'changeEmail'])->name('api.user.changeEmail');
            Route::post('change-password', [UserController::class, 'changePassword'])->name('api.user.changePassword');
        });

        Route::group(['prefix' => 'lesson'], function () {
            Route::get('list', [LessonController::class, 'getList'])->name('api.lesson.list');
            Route::get('progress', [LessonController::class, 'progress'])->name('api.lesson.progress');

            Route::group(['prefix' => 'topic'], function () {
                Route::get('{id}', [LessonTopicController::class, 'topic'])->name('api.lesson.topic.get');
                Route::post('pass/{id}', [LessonTopicController::class, 'pass'])->name('api.lesson.topic.pass');
            });

            Route::group(['prefix' => 'test'], function () {
                Route::get('is-open/{id}', [LessonTestController::class, 'isTestOpen'])->name('api.lesson.test.isOpen');
                Route::get('{id}', [LessonTestController::class, 'test'])->name('api.lesson.test.getTest');
                Route::post('check/{id}', [LessonTestController::class, 'check'])->name('api.lesson.test.check');
            });
        });

    });

    Route::get('positions/list', [PositionController::class, 'list'])->name('api.position.list');
    Route::get('mukhtasibats/list', [MukhtasibatController::class, 'list'])->name('api.mukhtasibat.list');
    Route::get('parishes/list', [ParishController::class, 'list'])->name('api.parish.list');

});
