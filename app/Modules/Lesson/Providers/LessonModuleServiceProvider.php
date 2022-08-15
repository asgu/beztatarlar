<?php


namespace Modules\Lesson\Providers;


use Netibackend\Laravel\Providers\BaseModuleServiceProvider;

class LessonModuleServiceProvider extends BaseModuleServiceProvider
{

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Lesson';
    }

    /**
     * @inheritDoc
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Lesson';
    }
}
