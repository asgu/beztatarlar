<?php

namespace Modules\Admin\Http\Controllers;


use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Dto\LessonDto;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Services\LessonService;
use Modules\Translation\Services\TranslationService;

class LessonController extends Controller
{

    /**
     * @var LessonService
     */
    private LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->lessonService->getFilteredDataTable()
            ->addColumn('status', function (Lesson $lesson) {
                return ActivityStatusFacade::statusLabel($lesson->status);
            })
            ->addColumn('action', function(Lesson $lesson) {
                return view('Admin::includes.actions', [
                    'id'        => $lesson->id,
                    'route'     => 'lesson',
                    'isView'    => true,
                    'isDeleted' => false,
                    'isEdit'    => true,
                ])->render();
            })
            ->blacklist(['action'])
            ->make(true);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('Admin::lesson.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $lesson = $this->lessonService->createDraw();
        return view('Admin::lesson.create', [
            'lesson' => $lesson,
            'translateLang' => $lesson->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param Lesson $lesson
     * @return View
     */
    public function view(Request $request, Lesson $lesson): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $lesson->setCurrentLanguage($lang);
        return view('Admin::lesson.view', [
            'lesson' => $lesson,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param Lesson $lesson
     * @return View
     */
    public function edit(Request $request, Lesson $lesson): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $lesson->setCurrentLanguage($lang);
        return view('Admin::lesson.edit', [
            'lesson' => $lesson,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function save(Request $request): RedirectResponse
    {
        $lesson = $this->lessonService->createDraw();
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $lesson = $this->lessonService->tryGetById($request->get('id'));
            $lesson->setCurrentLanguage($lang);
        }

        $dto = LessonDto::populateByArray($request->all());
        $this->lessonService->populate($lesson, $dto);
        $this->lessonService->save($lesson);

        return redirect()
            ->route('lesson.view', [
                'lesson' => $lesson->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param Lesson $lesson
     */
    public function destroy(Lesson $lesson)
    {
        $this->lessonService->destroy($lesson);
    }
}
