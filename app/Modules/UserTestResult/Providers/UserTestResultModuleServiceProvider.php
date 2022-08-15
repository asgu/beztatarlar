<?php


namespace Modules\UserTestResult\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class UserTestResultModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'UserTestResult';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\UserTestResult';
    }
}
