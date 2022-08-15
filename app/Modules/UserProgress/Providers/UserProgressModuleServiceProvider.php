<?php


namespace Modules\UserProgress\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class UserProgressModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'UserProgress';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\UserProgress';
    }
}
