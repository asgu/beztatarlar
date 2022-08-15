<?php


namespace Modules\Lesson\Search;


use Illuminate\Database\Eloquent\Builder;
use Modules\Lesson\Repositories\LessonRepository;
use Netibackend\Laravel\SearchProvider\SearchOffsetProvider;

class LessonSearchProvider extends SearchOffsetProvider
{
    /**
     * @var LessonRepository
     */
    private LessonRepository $repository;

    public function __construct(LessonRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return $this->repository->findAllActiveQuery();
    }
}
