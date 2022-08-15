<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\ApiLoggerController;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\CertificateController;
use Modules\Admin\Http\Controllers\FileController;
use Modules\Admin\Http\Controllers\LanguageMessagesController;
use Modules\Admin\Http\Controllers\LessonController;
use Modules\Admin\Http\Controllers\LessonTopicController;
use Modules\Admin\Http\Controllers\LoginSocialController;
use Modules\Admin\Http\Controllers\MukhtasibatController;
use Modules\Admin\Http\Controllers\ParishController;
use Modules\Admin\Http\Controllers\PositionController;
use Modules\Admin\Http\Controllers\SeedersController;
use Modules\Admin\Http\Controllers\TeacherController;
use Modules\Admin\Http\Controllers\TestAnswerController;
use Modules\Admin\Http\Controllers\TestController;
use Modules\Admin\Http\Controllers\TestQuestionController;
use Modules\Admin\Http\Controllers\TestQuestionLinkController;
use Modules\Admin\Http\Controllers\UsersController;

Route::get('/social/{provider}', [LoginSocialController::class, 'redirect'])->name('auth.social');

Route::group(['prefix' => 'admin', 'middleware' => []], function() {

    Route::group(['prefix' => 'auth', 'middleware' => []], function() {
        Route::group(['middleware' => ['guest']], function() {
            Route::get('login', [AuthController::class, 'showLoginForm'])->name('adminLogin');
        });

        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::group(['middleware' => ['auth:web', 'user.checkAccessAdminPanel']], function() {

        Route::post('file/upload', [FileController::class, 'uploadFile'])->name('admin.file.upload');
        Route::get('seed', [SeedersController::class, 'run'])->name('seed.run');

        Route::group(['prefix' => 'user'], function() {
            Route::get('index', [UsersController::class, 'index'])->name('user.index');
            Route::get('create', [UsersController::class, 'create'])->name('user.create');
            Route::get('edit/{user}', [UsersController::class, 'edit'])->name('user.edit');
            Route::get('view/{user}', [UsersController::class, 'view'])->name('user.view');
            Route::post('save', [UsersController::class, 'save'])->name('user.save');
            Route::get('data', [UsersController::class, 'data'])->name('user.data');
        });

        Route::group(['prefix' => 'position'], function() {
            Route::get('index', [PositionController::class, 'index'])->name('position.index');
            Route::get('create', [PositionController::class, 'create'])->name('position.create');
            Route::get('edit/{position}', [PositionController::class, 'edit'])->name('position.edit');
            Route::get('view/{position}', [PositionController::class, 'view'])->name('position.view');
            Route::post('save', [PositionController::class, 'save'])->name('position.save');
            Route::get('data', [PositionController::class, 'data'])->name('position.data');
            Route::delete('{position}', [PositionController::class, 'destroy'])->name('position.destroy');
        });

        Route::group(['prefix' => 'mukhtasibat'], function() {
            Route::get('index', [MukhtasibatController::class, 'index'])->name('mukhtasibat.index');
            Route::get('create', [MukhtasibatController::class, 'create'])->name('mukhtasibat.create');
            Route::get('edit/{mukhtasibat}', [MukhtasibatController::class, 'edit'])->name('mukhtasibat.edit');
            Route::get('view/{mukhtasibat}', [MukhtasibatController::class, 'view'])->name('mukhtasibat.view');
            Route::post('save', [MukhtasibatController::class, 'save'])->name('mukhtasibat.save');
            Route::get('data', [MukhtasibatController::class, 'data'])->name('mukhtasibat.data');
            Route::delete('{mukhtasibat}', [MukhtasibatController::class, 'destroy'])->name('mukhtasibat.destroy');
        });

        Route::group(['prefix' => 'parish'], function() {
            Route::get('index', [ParishController::class, 'index'])->name('parish.index');
            Route::get('create', [ParishController::class, 'create'])->name('parish.create');
            Route::get('edit/{parish}', [ParishController::class, 'edit'])->name('parish.edit');
            Route::get('view/{parish}', [ParishController::class, 'view'])->name('parish.view');
            Route::post('save', [ParishController::class, 'save'])->name('parish.save');
            Route::get('data', [ParishController::class, 'data'])->name('parish.data');
            Route::delete('{parish}', [ParishController::class, 'destroy'])->name('parish.destroy');
        });

        Route::group(['prefix' => 'teacher'], function() {
            Route::get('index', [TeacherController::class, 'index'])->name('teacher.index');
            Route::get('create', [TeacherController::class, 'create'])->name('teacher.create');
            Route::get('edit/{teacher}', [TeacherController::class, 'edit'])->name('teacher.edit');
            Route::get('view/{teacher}', [TeacherController::class, 'view'])->name('teacher.view');
            Route::post('save', [TeacherController::class, 'save'])->name('teacher.save');
            Route::get('data', [TeacherController::class, 'data'])->name('teacher.data');
            Route::delete('{teacher}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
        });

        Route::group(['prefix' => 'language-messages', 'middleware' => []], function() {
            Route::get('/', [LanguageMessagesController::class, 'index'])->name('languageMessages.index');
            Route::get('data', [LanguageMessagesController::class, 'data'])->name('languageMessages.data');
            Route::get('create', [LanguageMessagesController::class, 'create'])->name('languageMessages.create');
            Route::get('view/{id}', [LanguageMessagesController::class, 'view'])->name('languageMessages.view');
            Route::get('edit/{id}', [LanguageMessagesController::class, 'edit'])->name('languageMessages.edit');
            Route::patch('save', [LanguageMessagesController::class, 'save'])->name('languageMessages.save');
        });

        Route::group(['prefix' => 'certificate', 'middleware' => []], function() {
            Route::get('/', [CertificateController::class, 'index'])->name('certificate.index');
            Route::get('data', [CertificateController::class, 'data'])->name('certificate.data');
            Route::get('view/{id}', [CertificateController::class, 'view'])->name('certificate.view');
        });

        Route::group(['prefix' => 'lesson'], function() {
            Route::get('index', [LessonController::class, 'index'])->name('lesson.index');
            Route::get('create', [LessonController::class, 'create'])->name('lesson.create');
            Route::get('edit/{lesson}', [LessonController::class, 'edit'])->name('lesson.edit');
            Route::get('view/{lesson}', [LessonController::class, 'view'])->name('lesson.view');
            Route::post('save', [LessonController::class, 'save'])->name('lesson.save');
            Route::get('data', [LessonController::class, 'data'])->name('lesson.data');
            Route::delete('{lesson}', [LessonController::class, 'destroy'])->name('lesson.destroy');

            Route::group(['prefix' => 'view/{lessonId}/topic'], function() {
                Route::get('index', [LessonTopicController::class, 'index'])->name('lessonTopic.index');
                Route::get('create', [LessonTopicController::class, 'create'])->name('lessonTopic.create');
                Route::get('view/{id}', [LessonTopicController::class, 'view'])->name('lessonTopic.view');
                Route::get('edit/{id}', [LessonTopicController::class, 'edit'])->name('lessonTopic.edit');
                Route::post('save', [LessonTopicController::class, 'save'])->name('lessonTopic.save');
                Route::get('data', [LessonTopicController::class, 'data'])->name('lessonTopic.data');
            });
            Route::delete('topic/{id}', [LessonTopicController::class, 'destroy'])->name('lessonTopic.destroy');
        });

        Route::group(['prefix' => 'test', 'middleware' => []], function() {
            Route::get('/', [TestController::class, 'index'])->name('test.index');
            Route::get('data', [TestController::class, 'data'])->name('test.data');
            Route::get('create', [TestController::class, 'create'])->name('test.create');
            Route::get('view/{id}', [TestController::class, 'view'])->name('test.view');
            Route::get('edit/{id}', [TestController::class, 'edit'])->name('test.edit');
            Route::post('save', [TestController::class, 'save'])->name('test.save');
            Route::delete('{id}', [TestController::class, 'destroy'])->name('test.destroy');

            Route::group(['prefix' => '{testId}/question', 'middleware' => []], function() {
                Route::get('data', [TestQuestionLinkController::class, 'data'])->name('test.questionLink.data');

                Route::get('create', [TestQuestionController::class, 'create'])->name('test.question.create');
                Route::get('edit/{id}', [TestQuestionController::class, 'edit'])->name('test.question.edit');
                Route::post('store', [TestQuestionController::class, 'store'])->name('test.question.store');
                Route::post('update/{id}', [TestQuestionController::class, 'update'])->name('test.question.update');
                Route::get('view/{id}', [TestQuestionController::class, 'view'])->name('test.question.view');
                Route::delete('{id}', [TestQuestionController::class, 'destroy'])->name('test.question.destroy');
            });

            Route::group(['prefix' => 'question/{questionId}/answer', 'middleware' => []], function() {
                Route::get('data', [TestAnswerController::class, 'data'])->name('test.answer.data');

                Route::get('create', [TestAnswerController::class, 'create'])->name('test.answer.create');
                Route::get('edit/{id}', [TestAnswerController::class, 'edit'])->name('test.answer.edit');
                Route::post('save', [TestAnswerController::class, 'save'])->name('test.answer.save');
                Route::delete('{id}', [TestAnswerController::class, 'destroy'])->name('test.answer.destroy');
            });
        });
    });

    Route::group(['middleware' => ['auth:web']], function() {
        Route::group(['prefix' => 'api-logger'], function() {
            Route::get('/', [ApiLoggerController::class, 'index'])->name('apiLogger.index');
            Route::get('data', [ApiLoggerController::class, 'data'])->name('apiLogger.data');
            Route::get('view/{id}', [ApiLoggerController::class, 'view'])->name('apiLogger.view');
        });
    });
});
