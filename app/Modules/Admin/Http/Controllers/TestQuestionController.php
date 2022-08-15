<?php

namespace Modules\Admin\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Test\Modules\Question\Dto\TestQuestionDto;
use Modules\Test\Modules\Question\Services\TestQuestionService;
use Modules\Test\Modules\TestQuestionLink\Dto\TestQuestionLinkDto;
use Modules\Test\Services\TestService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TestQuestionController extends Controller
{

    /**
     * @var TestService
     */
    private TestService $testService;
    /**
     * @var TestQuestionService
     */
    private TestQuestionService $questionService;

    public function __construct(TestService $testService, TestQuestionService $questionService)
    {
        $this->testService = $testService;
        $this->questionService = $questionService;
    }

    /**
     * @param $testId
     * @return View
     */
    public function create($testId): View
    {
        $question = $this->questionService->createDraw();
        return view('Admin::testQuestion.create', [
            'question' => $question,
            'translateLang' => $question->getCurrentLanguage(),
            'testId' => $testId
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return View
     */
    public function view(Request $request, $testId, $id): View
    {
        $question = $this->questionService->tryGetById($id);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $question->setCurrentLanguage($lang);
        return view('Admin::testQuestion.view', [
            'question' => $question,
            'translateLang' => $lang,
            'testId' => $testId
        ]);
    }

    /**
     * @param Request $request
     * @param $testId
     * @param $id
     * @return View
     */
    public function edit(Request $request, $testId, $id): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $question = $this->questionService->tryGetById($id);
        $question->setCurrentLanguage($lang);
        return view('Admin::testQuestion.edit', [
            'question' => $question,
            'translateLang' => $lang,
            'testId' => $testId
        ]);
    }

    /**
     * @param Request $request
     * @param $testId
     * @param $id
     * @return RedirectResponse
     * @throws DataValidationException
     */
    public function update(Request $request, $testId, $id): RedirectResponse
    {
        $test = $this->testService->tryGetById($testId);
        $question = $this->questionService->tryGetById($id);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $question->setCurrentLanguage($lang);

        $dto = TestQuestionDto::populateByArray($request->all());
        $this->testService->updateQuestion($question, $dto);

        return redirect()
            ->route('test.question.view', [
                'testId' => $test->id,
                'id' => $question->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param Request $request
     * @param $testId
     * @return RedirectResponse
     * @throws DataValidationException
     */
    public function store(Request $request, $testId): RedirectResponse
    {
        $test = $this->testService->tryGetById($testId);
        $question = $this->questionService->createDraw();

        $dto = TestQuestionDto::populateByArray($request->all());
        $this->testService->attachQuestion($test, $question, $dto);

        return redirect()
            ->route('test.question.view', [
                'testId' => $test->id,
                'id' => $question->id,
                TranslationService::TRANSLATE_LANG_KEY => $request->get(TranslationService::TRANSLATE_LANG_KEY)
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param $testId
     * @param $questionId
     * @throws DataValidationException
     */
    public function destroy($testId, $questionId)
    {
        $question = $this->questionService->tryGetById($questionId);
        $test = $this->testService->tryGetById($testId);
        $this->testService->destroyQuestion($test, $question);
    }
}
