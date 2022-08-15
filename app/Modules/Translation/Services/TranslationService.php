<?php

namespace Modules\Translation\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Language\Services\LanguageService;

class TranslationService
{
    public const INTL_PREFIX        = '_intl';
    public const TRANSLATE_LANG_KEY = 'translate-lang';

    private LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function isDefaultLocale($lang): bool
    {
        return Str::lower($lang) === config()->get('app.defaultLocale');
    }

    public function getDefaultLocale(): string
    {
        return config()->get('app.defaultLocale');
    }

    public function getLanguages(): array
    {
        return $this->languageService->getAppLanguagesList();
    }

    // get all tables where translations
    public function getIntlTables(): array
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return array_filter($tables, function(string $tableName) {
            return str_contains($tableName, self::INTL_PREFIX);
        });
    }

    public function isIntlExists($tableOriginal): bool
    {
        return Schema::hasTable($tableOriginal . self::INTL_PREFIX);
    }
}
