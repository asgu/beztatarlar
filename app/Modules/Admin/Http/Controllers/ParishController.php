<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Parish\Dto\ParishDto;
use Modules\Parish\Models\Parish;
use Modules\Parish\Services\ParishService;
use Modules\Position\Models\Position;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class ParishController extends Controller
{
    /**
     * @var ParishService
     */
    private ParishService $parishService;

    public function __construct(ParishService $parishService)
    {
        $this->parishService = $parishService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->parishService->getFilteredDataTable()
            ->addColumn('status', function (Parish $parish) {
                return ActivityStatusFacade::statusLabel($parish->status);
            })
            ->addColumn('action', function (Parish $parish) {
                return view('Admin::includes.actions', [
                    'id'        => $parish->id,
                    'route'     => 'parish',
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
    public function index()
    {
        return view('Admin::parish.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $parish = $this->parishService->createDraw();
        return view('Admin::parish.create', [
            'parish' => $parish,
            'translateLang' => $parish->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param Parish $parish
     * @return View
     */
    public function view(Request $request, Parish $parish): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $parish->setCurrentLanguage($lang);
        return view('Admin::parish.view', [
            'parish' => $parish,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param Parish $parish
     * @return View
     */
    public function edit(Request $request, Parish $parish): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $parish->setCurrentLanguage($lang);
        return view('Admin::parish.edit', [
            'parish' => $parish,
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
        $parish = $this->parishService->createDraw();
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $parish = $this->parishService->tryGetById($request->get('id'));
            $parish->setCurrentLanguage($lang);
        }

        $dto = ParishDto::populateByArray($request->all());
        $this->parishService->populate($parish, $dto);
        $this->parishService->tryValidate($parish);
        $this->parishService->save($parish);

        return redirect()
            ->route('parish.view', [
                'parish' => $parish->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param Parish $parish
     */
    public function destroy(Parish $parish)
    {
        $this->parishService->destroy($parish);
    }
}
