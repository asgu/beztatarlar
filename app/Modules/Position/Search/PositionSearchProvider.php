<?php


namespace Modules\Position\Search;


use Illuminate\Database\Eloquent\Builder;
use Modules\Position\Repositories\PositionRepository;
use Netibackend\Laravel\SearchProvider\SearchOffsetProvider;

class PositionSearchProvider extends SearchOffsetProvider
{

    /**
     * @var PositionRepository
     */
    private PositionRepository $repository;

    public function __construct(PositionRepository $repository)
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
