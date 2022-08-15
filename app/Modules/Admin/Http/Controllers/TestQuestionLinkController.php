<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;
use Modules\Test\Modules\TestQuestionLink\Services\TestQuestionLinkService;
use Modules\Test\Services\TestService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TestQuestionLinkController extends Controller
{

    /**
     * @var TestQuestionLinkService
     */
    private TestQuestionLinkService $questionLinkService;
    /**
     * @var TestService
     */
    private TestService $testService;

    public function __construct(TestService $testService, TestQuestionLinkService $questionLinkService)
    {
        $this->questionLinkService = $questionLinkService;
        $this->testService = $testService;
    }

    /**
     * @param Request $request
     * @param $testId
     * @return JsonResponse
     * @throws Exception
     */
    public function data(Request $request, $testId): JsonResponse
    {
        $test = $this->testService->tryGetById($testId);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        return $this->questionLinkService->getFilteredDataTable($test)
            ->addColumn('action', function(TestQuestionLink $link){
                return view('Admin::includes.actions', [
                    'id'        => $link->question_id,
                    'isView'    => true,
                    'viewRoute' => route('test.question.view', ['testId' => $link->test_id, 'id' => $link->question_id]),
                    'isDeleted' => false,
                    'deleteRoute' => "test/{$link->test_id}/question",
                    'isEdit'    => true,
                    'editRoute' => route('test.question.edit', ['testId' => $link->test_id, 'id' => $link->question_id])
                ])->render();
            })
            ->orderColumn('question', function (Builder $query, $order) {
                $query
                    ->select(['test_question_links.*'])
                    ->leftJoin('test_questions', 'test_questions.id', '=', 'test_question_links.question_id')
                    ->orderBy('test_questions.question', $order)
                ;
            })
            ->addColumn('question', function (TestQuestionLink $link) use ($lang) {
                $link->question->setCurrentLanguage($lang);
                return $link->question->question;
            })
            ->blacklist(['action'])
            ->make(true);
    }

}
