<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Position\Dto\PositionDto;
use Modules\Position\Models\Position;
use Modules\Position\Services\PositionService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class PositionController extends Controller
{

    /**
     * @var PositionService
     */
    private PositionService $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->positionService->getFilteredDataTable()
            ->addColumn('status', function (Position $position) {
                return ActivityStatusFacade::statusLabel($position->status);
            })
            ->addColumn('action', function(Position $position) {
                return view('Admin::includes.actions', [
                    'id'        => $position->id,
                    'route'     => 'position',
                    'isView'    => true,
                    'isDeleted' => false,
                    'isEdit'    => true,
                ])->render();
            })
            ->blacklist(['action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('Admin::position.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $position = $this->positionService->createDraw();
        return view('Admin::position.create', [
            'position' => $position,
            'translateLang' => $position->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param Position $position
     * @return View
     */
    public function view(Request $request, Position $position): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $position->setCurrentLanguage($lang);
        return view('Admin::position.view', [
            'position' => $position,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param Position $position
     * @return View
     */
    public function edit(Request $request, Position $position): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $position->setCurrentLanguage($lang);
        return view('Admin::position.edit', [
            'position' => $position,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws DataValidationException
     */
    public function save(Request $request): RedirectResponse
    {
        $position = $this->positionService->createDraw();
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $position = $this->positionService->tryGetById($request->get('id'));
            $position->setCurrentLanguage($lang);
        }

        $dto = PositionDto::populateByArray($request->all());
        $this->positionService->populate($position, $dto);
        $this->positionService->tryValidate($position);
        $this->positionService->save($position);

        return redirect()
            ->route('position.view', [
                'position' => $position->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param Position $position
     */
    public function destroy(Position $position)
    {
        $this->positionService->destroy($position);
    }
}
