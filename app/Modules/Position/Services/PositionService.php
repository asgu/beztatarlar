<?php


namespace Modules\Position\Services;


use App\Api\Dto\SearchDto;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Position\Dto\PositionDto;
use Modules\Position\Factories\PositionFactory;
use Modules\Position\Models\Position;
use Modules\Position\Repositories\PositionRepository;
use Modules\Position\Search\PositionSearchProvider;
use Modules\Position\Validation\RuleValidation\PositionValidator;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\SearchProvider\Interfaces\SearchProviderResultInterface;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class PositionService
{
    /**
     * @var PositionFactory
     */
    private PositionFactory $factory;
    /**
     * @var PositionRepository
     */
    private PositionRepository $repository;

    public function __construct(PositionFactory $factory, PositionRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @return Position
     */
    public function createDraw(): Position
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return Position|null
     */
    public function getById($id): ?Position
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return Position
     */
    public function tryGetById($id): Position
    {
        $position = $this->getById($id);
        $this->ensureExists($position);
        return $position;
    }

    /**
     * @param Position $position
     * @param PositionDto $dto
     * @throws Exception
     */
    public function populate(Position $position, PositionDto $dto): void
    {
        $position->fill($dto->toArray());
    }

    /**
     * @param Position $position
     * @throws DataValidationException
     */
    public function tryValidate(Position $position): void
    {
        PositionValidator::validateStatic($position);
    }

    /**
     * @param Position $position
     */
    public function save(Position $position): void
    {
        $this->repository->save($position);
    }

    /**
     * @param Position $position
     */
    public function destroy(Position $position): void
    {
        $position->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($position);
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
     * @param Position|null $position
     */
    protected function ensureExists(?Position $position): void
    {
        if (!$position) {
            throw new NotFoundException(__('app.position.errors.notFound'));
        }
    }

    /**
     * @param SearchDto $searchDto
     * @return SearchProviderResultInterface
     * @throws DataValidationException
     * @throws BindingResolutionException
     */
    public function getAllActive(SearchDto $searchDto): SearchProviderResultInterface
    {
        $provider = new PositionSearchProvider($this->repository);
        $provider->setPagination($searchDto->offset, $searchDto->limit);
        return $provider->search();
    }
}
