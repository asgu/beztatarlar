<?php

namespace App\Translation;

use App\Support\JsonSupport;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Redis;
use Illuminate\Translation\FileLoader;
use JsonException;
use Modules\Language\Services\LanguageMessagesService;
use Modules\Language\Services\LanguageService;

class DatabaseLoader extends FileLoader
{
    private LanguageMessagesService $languageMessagesService;
    private LanguageService $languageService;

    public function __construct(Filesystem $files, $path)
    {
        $this->languageMessagesService = app()->make(LanguageMessagesService::class);
        $this->languageService = app()->make(LanguageService::class);

        parent::__construct($files, $path);
    }

    /**
     * @param string $locale
     * @param string $group
     * @param null $namespace
     *
     * @return array
     * @throws JsonException
     */
    public function load($locale, $group, $namespace = null): array
    {
        if ($namespace !== null && $namespace !== '*') {
            return $this->loadNamespaced($locale, $group, $namespace);
        }

        return $this->loadCache($locale, $group);
    }

    private function loadCache($locale, $group): array
    {
        $valuesJson = Redis::connection()
            ->command('get', [$this->generationCacheKey($locale, $group)]);

        if ($valuesJson !== null) {
            return JsonSupport::decode($valuesJson);
        }

        $values = $this->loadDatabase($locale, $group);

        Redis::connection()
            ->command('set', [$this->generationCacheKey($locale, $group), JsonSupport::encode($values)]);

        return $values;
    }

    private function loadDatabase($locale, $group): array
    {
        $appLanguage = $this->languageService->getByCode($locale);

        if ($appLanguage) {
            $languageMessages = $this->languageMessagesService->getBackendMessagesByAppLang($appLanguage);
            if ($languageMessages && is_array($languageMessages->message_values)) {
                return $languageMessages->message_values[$group] ?? [];
            }
        }

        return [];
    }

    private function generationCacheKey($locale, $group): string
    {
        return $this->languageMessagesService->generationCacheKey($locale, $group);
    }
}
