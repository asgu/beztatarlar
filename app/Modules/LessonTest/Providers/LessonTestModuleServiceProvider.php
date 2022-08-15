<?php


namespace Modules\LessonTest\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class LessonTestModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'LessonTest';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\LessonTest';
    }
}
