<?php

namespace Modules\Auth\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class AuthModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'Auth';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Auth';
    }
}
