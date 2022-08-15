<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Dto\LessonTopicDto;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;
use Modules\Lesson\Services\LessonService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class LessonTopicController extends Controller
{
    /**
     * @var LessonTopicService
     */
    private LessonTopicService $topicService;
    /**
     * @var LessonService
     */
    private LessonService $lessonService;

    public function __construct(LessonService $lessonService, LessonTopicService $topicService)
    {
        $this->topicService = $topicService;
        $this->lessonService = $lessonService;
    }

    /**
     * @param $lessonId
     * @return JsonResponse
     * @throws Exception
     */
    public function data(Request $request, $lessonId): JsonResponse
    {
        $lesson = $this->lessonService->tryGetById($lessonId);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);

        return $this->topicService->getFilteredDataTable($lesson)
            ->addColumn('topic.id', function (LessonTopic $topic) {
                return $topic->id;
            })
            ->addColumn('topic.priority', function (LessonTopic $topic) {
                return $topic->priority;
            })
            ->addColumn('topic.status', function (LessonTopic $topic) {
                return ActivityStatusFacade::statusLabel($topic->status);
            })
            ->addColumn('topic.title', function (LessonTopic $topic) use ($lang) {
                $topic->setCurrentLanguage($lang);
                return $topic->getAttribute('title');
            })
            ->addColumn('action', function(LessonTopic $topic) use ($lessonId) {
                return view('Admin::includes.actions', [
                    'id' => $topic->id,
                    'viewRoute'     => route('lessonTopic.view', [
                        'lessonId' => $lessonId,
                        'id' => $topic->id
                    ]),
                    'isView'    => true,
                    'isDeleted' => false,
                    'deleteRoute' => "lesson/topic",
                    'isEdit'    => true,
                    'editRoute' => route('lessonTopic.edit', [
                        'lessonId' => $lessonId,
                        'id' => $topic->id
                    ])
                ])->render();
            })
            ->blacklist(['action'])
            ->make(true);
    }

    /**
     * @param $lessonId
     * @return View
     */
    public function index($lessonId): View
    {
        return view('Admin::lessonTopic.index', ['lessonId' => $lessonId]);
    }

    /**
     * @param $lessonId
     * @return View
     */
    public function create($lessonId): View
    {
        $lesson = $this->lessonService->tryGetById($lessonId);
        $lessonTopic = $this->topicService->createDraw($lesson);
        return view('Admin::lessonTopic.create', [
            'lessonTopic' => $lessonTopic,
            'translateLang' => $lessonTopic->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return View
     */
    public function view(Request $request, $lessonId, $id): View
    {
        $lessonTopic = $this->topicService->tryGetById($id);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $lessonTopic->setCurrentLanguage($lang);
        return view('Admin::lessonTopic.view', [
            'lessonTopic' => $lessonTopic,
            'translateLang' => $lang,
            'lessonId' => $lessonId
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return View
     */
    public function edit(Request $request, $lessonId, $id): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $lessonTopic = $this->topicService->tryGetById($id);
        $lessonTopic->setCurrentLanguage($lang);
        return view('Admin::lessonTopic.edit', [
            'lessonTopic' => $lessonTopic,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param $lessonId
     * @return RedirectResponse
     * @throws DataValidationException
     * @throws Exception
     */
    public function save(Request $request, $lessonId): RedirectResponse
    {
        $lesson = $this->lessonService->tryGetById($lessonId);
        $lessonTopic = $this->topicService->createDraw($lesson);
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $lessonTopic = $this->topicService->tryGetById($request->get('id'));
            $lessonTopic->setCurrentLanguage($lang);
        }

        $dto = LessonTopicDto::populateByArray($request->all());
        $this->topicService->populate($lessonTopic, $dto);
        $this->topicService->save($lessonTopic);

        return redirect()
            ->route('lesson.view', [
                'lesson' => $lessonId,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param $id
     * @throws DataValidationException
     */
    public function destroy($id)
    {
        $topic = $this->topicService->getById($id);
        if ($topic) {
            $this->topicService->destroy($topic);
        }
    }
}
