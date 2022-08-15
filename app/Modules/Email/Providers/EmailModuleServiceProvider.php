<?php


namespace Modules\Email\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class EmailModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Email';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Email';
    }
}
