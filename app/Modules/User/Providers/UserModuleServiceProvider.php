<?php

namespace Modules\User\Providers;

use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class UserModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'User';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\User';
    }
}
