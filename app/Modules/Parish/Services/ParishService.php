<?php


namespace Modules\Parish\Services;


use App\Api\Dto\SearchDto;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Parish\Dto\ParishDto;
use Modules\Parish\Factories\ParishFactory;
use Modules\Parish\Models\Parish;
use Modules\Parish\Repositories\ParishRepository;
use Modules\Parish\Search\ParishSearchProvider;
use Modules\Parish\Validation\RuleValidation\ParishValidator;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\SearchProvider\Interfaces\SearchProviderResultInterface;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class ParishService
{
    /**
     * @var ParishRepository
     */
    private ParishRepository $repository;
    /**
     * @var ParishFactory
     */
    private ParishFactory $factory;

    public function __construct(ParishRepository $repository, ParishFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @return Parish
     */
    public function createDraw(): Parish
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return Parish|null
     */
    public function getById($id): ?Parish
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return Parish
     */
    public function tryGetById($id): Parish
    {
        $parish = $this->getById($id);
        $this->ensureExists($parish);
        return $parish;
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
     * @param Parish $parish
     * @param ParishDto $dto
     * @throws Exception
     */
    public function populate(Parish $parish, ParishDto $dto): void
    {
        $parish->fill($dto->toArray());
    }

    /**
     * @param Parish $parish
     * @throws DataValidationException
     */
    public function tryValidate(Parish $parish): void
    {
        ParishValidator::validateStatic($parish);
    }

    /**
     * @param Parish $parish
     */
    public function save(Parish $parish): void
    {
        $this->repository->save($parish);
    }

    /**
     * @param Parish $parish
     */
    public function destroy(Parish $parish): void
    {
        $parish->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($parish);
    }

    /**
     * @param Parish|null $parish
     */
    protected function ensureExists(?Parish $parish): void
    {
        if (!$parish) {
            throw new NotFoundException(__('app.parish.errors.notFound'));
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
        $provider = new ParishSearchProvider($this->repository);
        $provider->setPagination($dto->offset, $dto->limit);
        return $provider->search();
    }
}
