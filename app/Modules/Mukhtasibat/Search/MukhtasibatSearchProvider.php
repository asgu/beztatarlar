<?php


namespace Modules\Mukhtasibat\Search;


use Illuminate\Database\Eloquent\Builder;
use Modules\Mukhtasibat\Repositories\MukhtasibatRepository;
use Netibackend\Laravel\SearchProvider\SearchOffsetProvider;

class MukhtasibatSearchProvider extends SearchOffsetProvider
{
    /**
     * @var MukhtasibatRepository
     */
    private MukhtasibatRepository $repository;

    public function __construct(MukhtasibatRepository $repository)
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
