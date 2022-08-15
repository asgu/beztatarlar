<?php


namespace Modules\Admin\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Language\Services\LanguageMessagesService;

class SeedersController extends Controller
{
    /**
     * @var LanguageMessagesService
     */
    private LanguageMessagesService $languageMessagesService;

    public function __construct(LanguageMessagesService $languageMessagesService)
    {
        $this->languageMessagesService = $languageMessagesService;
    }

    public function run(Request $request)
    {
        $seederClass = $request->get('class');
        if ($seederClass) {
            $seeder = new $seederClass();
            $seeder->run();
        }

        $this->languageMessagesService->clearBackendLanguagesCache('RU');
        $this->languageMessagesService->clearBackendLanguagesCache('TT');
        return redirect()->route('user.index');
    }
}
