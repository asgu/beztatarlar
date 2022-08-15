<?php


namespace Modules\ActivityStatus\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class ActivityStatusModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'ActivityStatus';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\ActivityStatus';
    }
}
