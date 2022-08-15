<?php


namespace Modules\Certificate\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class CertificateModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Certificate';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Certificate';
    }
}
