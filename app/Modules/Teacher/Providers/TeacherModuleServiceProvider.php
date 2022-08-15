<?php


namespace Modules\Teacher\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class TeacherModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Teacher';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Teacher';
    }
}
