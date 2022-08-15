<?php

namespace Modules\Language\Console\Commands;

use Illuminate\Console\Command;
use Modules\Language\Services\LanguageService;

class ChangeSortIndexCommand extends Command
{
    protected $signature = 'language:change:sort';
    protected $description = 'Change languages sort indexes ';

    private LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $esLang = $this->languageService->getByCode('ES');
        $esLang->sort_index = 1;
        $esLang->save();

        $ruLang = $this->languageService->getByCode('RU');
        $ruLang->sort_index = 3;
        $ruLang->save();
    }
}
