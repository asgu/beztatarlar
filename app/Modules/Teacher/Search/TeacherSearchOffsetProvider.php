<?php


namespace Modules\Teacher\Search;


use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Teacher\Models\Teacher;
use Netibackend\Laravel\SearchProvider\SearchOffsetProvider;

class TeacherSearchOffsetProvider extends SearchOffsetProvider
{

    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return Teacher::query()
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE);
    }
}
