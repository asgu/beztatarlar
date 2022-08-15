<?php

namespace Modules\Translation\Services;

use Illuminate\Support\Facades\Facade;

/**
 * Class TranslationServiceFacade
 * @package Modules\Translation\Services
 *
 * @method static bool isDefaultLocale($lang)
 * @method static string getDefaultLocale()
 */
class TranslationServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TranslationService::class;
    }
}
