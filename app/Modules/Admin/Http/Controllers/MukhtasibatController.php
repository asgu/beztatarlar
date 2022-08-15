<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Mukhtasibat\Dto\MukhtasibatDto;
use Modules\Mukhtasibat\Models\Mukhtasibat;
use Modules\Mukhtasibat\Services\MukhtasibatService;
use Modules\Translation\Services\TranslationService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class MukhtasibatController extends Controller
{
    /**
     * @var MukhtasibatService
     */
    private MukhtasibatService $mukhtasibatService;

    public function __construct(MukhtasibatService $mukhtasibatService)
    {
        $this->mukhtasibatService = $mukhtasibatService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->mukhtasibatService->getFilteredDataTable()
            ->addColumn('status', function (Mukhtasibat $mukhtasibat) {
                return ActivityStatusFacade::statusLabel($mukhtasibat->status);
            })
            ->addColumn('action', function(Mukhtasibat $mukhtasibat) {
                return view('Admin::includes.actions', [
                    'id'        => $mukhtasibat->id,
                    'route'     => 'mukhtasibat',
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
        return view('Admin::mukhtasibat.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $mukhtasibat = $this->mukhtasibatService->createDraw();
        return view('Admin::mukhtasibat.create', [
            'mukhtasibat' => $mukhtasibat,
            'translateLang' => $mukhtasibat->getCurrentLanguage(),
        ]);
    }

    /**
     * @param Request $request
     * @param Mukhtasibat $mukhtasibat
     * @return View
     */
    public function view(Request $request, Mukhtasibat $mukhtasibat): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $mukhtasibat->setCurrentLanguage($lang);
        return view('Admin::mukhtasibat.view', [
            'mukhtasibat' => $mukhtasibat,
            'translateLang' => $lang,
        ]);
    }

    /**
     * @param Request $request
     * @param Mukhtasibat $mukhtasibat
     * @return View
     */
    public function edit(Request $request, Mukhtasibat $mukhtasibat): View
    {
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        $mukhtasibat->setCurrentLanguage($lang);
        return view('Admin::mukhtasibat.edit', [
            'mukhtasibat' => $mukhtasibat,
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
        $mukhtasibat = $this->mukhtasibatService->createDraw();
        $lang = $request->get(TranslationService::TRANSLATE_LANG_KEY);
        if ($request->get('id')) {
            $mukhtasibat = $this->mukhtasibatService->tryGetById($request->get('id'));
            $mukhtasibat->setCurrentLanguage($lang);
        }

        $dto = MukhtasibatDto::populateByArray($request->all());
        $this->mukhtasibatService->populate($mukhtasibat, $dto);
        $this->mukhtasibatService->tryValidate($mukhtasibat);
        $this->mukhtasibatService->save($mukhtasibat);

        return redirect()
            ->route('mukhtasibat.view', [
                'mukhtasibat' => $mukhtasibat->id,
                TranslationService::TRANSLATE_LANG_KEY => $lang
            ])
            ->withInput()
            ->with(['success' => __('Успешно сохранено.')]);
    }

    /**
     * @param Mukhtasibat $mukhtasibat
     */
    public function destroy(Mukhtasibat $mukhtasibat)
    {
        $this->mukhtasibatService->destroy($mukhtasibat);
    }
}
