<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Language\Dto\AppLanguageMessageSaveDto;
use Modules\Language\Helpers\LanguageHelper;
use Modules\Language\Models\AppLanguageMessage;
use Modules\Language\Services\LanguageMessagesService;
use Modules\Language\Services\LanguageService;
use Yajra\DataTables\Facades\DataTables;

class LanguageMessagesController extends Controller
{
    private LanguageMessagesService $languageMessagesService;
    private LanguageService $languageService;

    public function __construct(
        LanguageMessagesService $languageMessagesService,
        LanguageService $languageService,
    )
    {
        $this->languageMessagesService = $languageMessagesService;
        $this->languageService = $languageService;
    }

    public function data(): JsonResponse
    {
        $languageMessages = $this->languageMessagesService->getListQuery();

        return DataTables::eloquent($languageMessages)
            ->addColumn('code', function (AppLanguageMessage $message) {
                return (string)$message->code;
            })
            ->addColumn('type', function (AppLanguageMessage $message) {
                return LanguageHelper::getLanguageMessageTypesLabel($message);
            })
            ->addColumn('action', function (AppLanguageMessage $message) {
                return view('Admin::includes.actions', [
                    'id' => $message->id,
                    'route' => 'languageMessages',
                    'isView' => true,
                    'isEdit'    => true,
                    'isDeleted' => true
                ])->render();
            })
            ->blacklist(['action'])
            ->make(true);
    }

    public function index(): Factory|View|Application
    {
        return view('Admin::languageMessages.index');
    }

    public function view(int $id): Factory|View|Application
    {
        return view('Admin::languageMessages.show', [
            'model' => $this->languageMessagesService->getTryById($id),
        ]);
    }

    public function create(): Factory|View|Application
    {
        $model = $this->languageMessagesService->createDraw();
        $languages = $this->languageService->getAppLanguagesLabels();

        return view('Admin::languageMessages.create', [
            'model' => $model,
            'languages' => $languages
        ]);
    }

    public function edit(int $id): Factory|View|Application
    {
        $model = $this->languageMessagesService->getTryById($id);
        $languages = $this->languageService->getAppLanguagesLabels();

        return view('Admin::languageMessages.edit', [
            'model' => $model,
            'languages' => $languages
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $languageMessage = $request->get('id') ?
            $this->languageMessagesService->getTryById($request->get('id')) :
            $this->languageMessagesService->createDraw();

        $this->languageMessagesService->populate($languageMessage, AppLanguageMessageSaveDto::populateByArray($request->all()));
        $this->languageMessagesService->save($languageMessage);

        return redirect()
            ->route('languageMessages.view', $languageMessage->id)
            ->withInput()
            ->with(['success' => 'Successful save']);
    }
}
