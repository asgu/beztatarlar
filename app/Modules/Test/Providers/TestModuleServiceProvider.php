<?php


namespace Modules\Test\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class TestModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Test';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Test';
    }
}
