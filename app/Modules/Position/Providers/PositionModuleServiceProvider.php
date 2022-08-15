<?php


namespace Modules\Position\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class PositionModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Position';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Position';
    }
}
