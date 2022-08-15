<?php


namespace Modules\Mukhtasibat\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class MukhtasibatModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Mukhtasibat';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Mukhtasibat';
    }
}
