<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Teacher\Dto\TeacherDto;
use Modules\Teacher\Models\Teacher;
use Modules\Teacher\Services\TeacherService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TeacherController extends Controller
{
    /**
     * @var TeacherService
     */
    private TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->teacherService->getFilteredDataTable()
            ->addColumn('status', function (Teacher $teacher) {
                return ActivityStatusFacade::statusLabel($teacher->status);
            })
            ->addColumn('action', function(Teacher $teacher) {
                return view('Admin::includes.actions', [
                    'id'        => $teacher->id,
                    'route'     => 'teacher',
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
        return view('Admin::teacher.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $teacher = $this->teacherService->createDraw();
        return view('Admin::teacher.create', [
            'teacher' => $teacher,
            'translateLang' => $teacher->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param Teacher $teacher
     * @return View
     */
    public function view(Request $request, Teacher $teacher): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $teacher->setCurrentLanguage($lang);
        return view('Admin::teacher.view', [
            'teacher' => $teacher,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param Teacher $teacher
     * @return View
     */
    public function edit(Request $request, Teacher $teacher): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $teacher->setCurrentLanguage($lang);
        return view('Admin::teacher.edit', [
            'teacher' => $teacher,
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
        $teacher = $this->teacherService->createDraw();
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $teacher = $this->teacherService->tryGetById($request->get('id'));
            $teacher->setCurrentLanguage($lang);
        }

        $dto = TeacherDto::populateByArray($request->all());
        $this->teacherService->populate($teacher, $dto);
        $this->teacherService->tryValidate($teacher);
        $this->teacherService->save($teacher);

        return redirect()
            ->route('teacher.view', [
                'teacher' => $teacher->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param Teacher $teacher
     * @throws Exception
     */
    public function destroy(Teacher $teacher)
    {
        $this->teacherService->destroy($teacher);
    }
}
