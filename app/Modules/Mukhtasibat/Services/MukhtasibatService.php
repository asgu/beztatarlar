<?php


namespace Modules\Mukhtasibat\Services;


use App\Api\Dto\SearchDto;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Mukhtasibat\Dto\MukhtasibatDto;
use Modules\Mukhtasibat\Factories\MukhtasibatFactory;
use Modules\Mukhtasibat\Models\Mukhtasibat;
use Modules\Mukhtasibat\Repositories\MukhtasibatRepository;
use Modules\Mukhtasibat\Search\MukhtasibatSearchProvider;
use Modules\Mukhtasibat\Validation\RuleValidation\MukhtasibatValidator;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\SearchProvider\Interfaces\SearchProviderResultInterface;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class MukhtasibatService
{
    /**
     * @var MukhtasibatRepository
     */
    private MukhtasibatRepository $repository;
    /**
     * @var MukhtasibatFactory
     */
    private MukhtasibatFactory $factory;

    public function __construct(MukhtasibatRepository $repository, MukhtasibatFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @return Mukhtasibat
     */
    public function createDraw(): Mukhtasibat
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return Mukhtasibat|null
     */
    public function getById($id): ?Mukhtasibat
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return Mukhtasibat
     */
    public function tryGetById($id): Mukhtasibat
    {
        $mukhtasibat = $this->getById($id);
        $this->ensureExists($mukhtasibat);
        return $mukhtasibat;
    }

    /**
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->getQuery())
            ->filterColumn('status', function (Builder $query, $status) {
                $query->where('status', $status);
            });
    }

    /**
     * @param Mukhtasibat $mukhtasibat
     * @param MukhtasibatDto $dto
     * @throws Exception
     */
    public function populate(Mukhtasibat $mukhtasibat, MukhtasibatDto $dto): void
    {
        $mukhtasibat->fill($dto->toArray());
    }

    /**
     * @param Mukhtasibat $mukhtasibat
     * @throws DataValidationException
     */
    public function tryValidate(Mukhtasibat $mukhtasibat): void
    {
        MukhtasibatValidator::validateStatic($mukhtasibat);
    }

    /**
     * @param Mukhtasibat $mukhtasibat\
     */
    public function save(Mukhtasibat $mukhtasibat): void
    {
        $this->repository->save($mukhtasibat);
    }

    /**
     * @param Mukhtasibat $mukhtasibat
     */
    public function destroy(Mukhtasibat $mukhtasibat): void
    {
        $mukhtasibat->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($mukhtasibat);
    }

    /**
     * @param Mukhtasibat|null $mukhtasibat
     */
    protected function ensureExists(?Mukhtasibat $mukhtasibat): void
    {
        if (!$mukhtasibat) {
            throw new NotFoundException(__('app.mukhtasibat.errors.notFound'));
        }
    }

    /**
     * @param SearchDto $dto
     * @return SearchProviderResultInterface
     * @throws DataValidationException
     * @throws BindingResolutionException
     */
    public function getAllActive(SearchDto $dto): SearchProviderResultInterface
    {
        $provider = new MukhtasibatSearchProvider($this->repository);
        $provider->setPagination($dto->offset, $dto->limit);
        return $provider->search();
    }
}
