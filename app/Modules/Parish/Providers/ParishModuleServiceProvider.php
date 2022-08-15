<?php


namespace Modules\Parish\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class ParishModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Parish';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Parish';
    }
}
