<?php


namespace Modules\Parish\Search;


use Illuminate\Database\Eloquent\Builder;
use Modules\Parish\Repositories\ParishRepository;
use Netibackend\Laravel\SearchProvider\SearchOffsetProvider;

class ParishSearchProvider extends SearchOffsetProvider
{

    /**
     * @var ParishRepository
     */
    private ParishRepository $repository;

    public function __construct(ParishRepository $repository)
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
