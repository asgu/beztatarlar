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
use Modules\Lesson\Services\LessonService;
use Modules\Test\Dto\TestDto;
use Modules\Test\Models\Test;
use Modules\Test\Services\TestService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TestController extends Controller
{
    /**
     * @var TestService
     */
    private TestService $testService;
    /**
     * @var LessonService
     */
    private LessonService $lessonService;

    public function __construct(TestService $testService, LessonService $lessonService)
    {
        $this->testService = $testService;
        $this->lessonService = $lessonService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->testService->getFilteredDataTable()
            ->addColumn('status', function (Test $test) {
                return ActivityStatusFacade::statusLabel($test->status);
            })
            ->addColumn('lesson', function (Test $test) {
                return $test->lesson ? $test->lesson->title : '-';
            })
            ->addColumn('action', function(Test $test) {
                return view('Admin::includes.actions', [
                    'id'        => $test->id,
                    'route'     => 'test',
                    'isView'    => true,
                    'isDeleted' => false,
                    'isEdit'    => true,
                ])->render();
            })
            ->orderColumn('lesson', function (Builder $query, $order) {
                $query
                    ->select(['tests.*'])
                    ->leftJoin('lesson_tests', 'tests.id', '=', 'lesson_tests.test_id')
                    ->leftJoin('lessons', 'lessons.id', '=', 'lesson_tests.lesson_id')
                    ->where('lesson_tests.status', ActivityStatusFacade::STATUS_ACTIVE)
                    ->orderBy('lessons.title', $order)
                    ->groupBy(['tests.id', 'lessons.title'])
                ;
            })
            ->blacklist(['action'])
            ->make(true);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('Admin::test.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $test = $this->testService->createDraw();
        $lessons = $this->lessonService->getAllWithoutTest()->pluck('title', 'id');

        return view('Admin::test.create', [
            'test' => $test,
            'lessons' => $lessons,
            'translateLang' => $test->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return View
     */
    public function view(Request $request, $id): View
    {
        $test = $this->testService->tryGetById($id);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($test->lesson) {
            $test->lesson->setCurrentLanguage($lang);
        }
        $test->setCurrentLanguage($lang);
        return view('Admin::test.view', [
            'test' => $test,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return View
     */
    public function edit(Request $request, $id): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $test = $this->testService->tryGetById($id);
        $lessons = $this->lessonService->getAllWithoutTest($lang)->pluck('title', 'id');
        if ($test->lesson) {
            $test->lesson->setCurrentLanguage($lang);
            $lessons[$test->lesson->id] = $test->lesson->title;
        }
        $test->setCurrentLanguage($lang);
        return view('Admin::test.edit', [
            'test' => $test,
            'lessons' => $lessons,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws DataValidationException
     * @throws Exception
     */
    public function save(Request $request): RedirectResponse
    {
        $test = $this->testService->createDraw();
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $test = $this->testService->tryGetById($request->get('id'));
            $test->setCurrentLanguage($lang);
        }

        $dto = TestDto::populateByArray($request->all());
        $this->testService->populate($test, $dto);
        $this->testService->save($test);

        return redirect()
            ->route('test.view', [
                'id' =>  $test->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        $this->testService->destroyById($id);
    }
}
