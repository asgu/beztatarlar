<?php

namespace Modules\Language\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class LanguageModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'Language';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Language';
    }
}
