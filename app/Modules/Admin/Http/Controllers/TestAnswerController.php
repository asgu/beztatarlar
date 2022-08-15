<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BooleanHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Modules\Test\Modules\Answer\Dto\TestAnswerDto;
use Modules\Test\Modules\Answer\Facades\AnswerFacade;
use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Answer\Services\TestAnswerService;
use Modules\Test\Modules\Question\Services\TestQuestionService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class TestAnswerController extends Controller
{

    /**
     * @var TestAnswerService
     */
    private TestAnswerService $answerService;
    /**
     * @var TestQuestionService
     */
    private TestQuestionService $questionService;

    public function __construct(TestAnswerService $answerService, TestQuestionService $questionService)
    {
        $this->answerService = $answerService;
        $this->questionService = $questionService;
    }

    /**
     * @param Request $request
     * @param $questionId
     * @return JsonResponse
     * @throws Exception
     */
    public function data(Request $request, $questionId): JsonResponse
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        return DataTables::eloquent($this->answerService->getQuery($questionId))
            ->addColumn('action', function(TestAnswer $answer) use ($questionId) {
                return view('Admin::includes.actions', [
                    'id' => $answer->id,
                    'isView'    => false,
                    'isDeleted' => AnswerFacade::canDelete($answer) ? false : true,
                    'isEdit'    => true,
                    'editRoute' => route('test.answer.edit', ['questionId' => $questionId, 'id' => $answer->id]),
                    'deleteRoute' => "test/question/{$questionId}/answer",
                ])->render();
            })
            ->addColumn('answer', function (TestAnswer $answer) use ($lang) {
                $answer->setCurrentLanguage($lang);
                return $answer->answer;
            })
            ->addColumn('is_correct', function (TestAnswer $answer) {
                return BooleanHelper::toHtml($answer->is_correct);
            })
            ->blacklist(['action'])
            ->make(true);
    }

    /**
     * @param $questionId
     * @return View
     */
    public function create($questionId): View
    {
        $question = $this->questionService->tryGetById($questionId);
        $answer = $this->answerService->createIncorrectDraw($question);
        return view('Admin::testAnswer.create', [
            'answer' => $answer,
            'translateLang' => $answer->getCurrentLanguage(),
            'question' => $question
        ]);
    }

    /**
     * @param Request $request
     * @param $questionId
     * @param $id
     * @return View
     */
    public function edit(Request $request, $questionId, $id): View
    {
        $question = $this->questionService->tryGetById($questionId);
        $answer = $this->answerService->tryGetById($id);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $question->setCurrentLanguage($lang);
        $answer->setCurrentLanguage($lang);
        return view('Admin::testAnswer.edit', [
            'answer' => $answer,
            'translateLang' => $lang,
            'question' => $question
        ]);
    }

    /**
     * @param Request $request
     * @param $questionId
     * @return RedirectResponse
     * @throws DataValidationException
     * @throws Exception
     */
    public function save(Request $request, $questionId): RedirectResponse
    {
        $question = $this->questionService->tryGetById($questionId);
        $answer = $this->answerService->createIncorrectDraw($question);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $answer = $this->answerService->tryGetById($request->get('id'));
            $answer->setCurrentLanguage($lang);
        }

        $dto = TestAnswerDto::populateByArray($request->all());
        $this->answerService->populate($answer, $dto);
        $this->answerService->save($answer);

        return redirect()
            ->route('test.question.view', [
                'testId' => $question->link->test_id,
                'id' => $question->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param $questionId
     * @param $id
     */
    public function destroy($questionId, $id)
    {
        $this->answerService->destroyById($id);
    }
}
