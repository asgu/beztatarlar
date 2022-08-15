<?php


namespace Modules\Lesson\Services;


use App\Api\Dto\SearchDto;
use App\Dto\PaginationDto;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Dto\LessonDto;
use Modules\Lesson\Factories\LessonFactory;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Repositories\LessonRepository;
use Modules\Lesson\Search\LessonSearchProvider;
use Modules\Lesson\Validation\RulesValidation\LessonValidator;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\SearchProvider\Interfaces\SearchProviderResultInterface;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class LessonService
{
    /**
     * @var LessonRepository
     */
    private LessonRepository $repository;
    /**
     * @var LessonFactory
     */
    private LessonFactory $factory;

    public function __construct(LessonRepository $repository, LessonFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
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
     * @return Lesson
     */
    public function createDraw(): Lesson
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return Lesson|null
     */
    public function getById($id): ?Lesson
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return Lesson
     */
    public function tryGetById($id): Lesson
    {
        $lesson = $this->getById($id);
        $this->ensureExists($lesson);
        return $lesson;
    }

    /**
     * @param string|null $lang
     * @return Collection
     */
    public function getAllWithoutTest(?string $lang = null): Collection
    {
        $lessons = $this->repository->findWithoutTest();
        if ($lang) {
            foreach ($lessons->all() as $lesson) {
                $lesson->setCurrentLanguage($lang);
            }
        }
        return $lessons;
    }

    public function getAllByTitle($title): Collection
    {
        return $this->repository->findByTitle($title);
    }

    /**
     * @param SearchDto $dto
     * @return SearchProviderResultInterface
     * @throws BindingResolutionException
     * @throws DataValidationException
     */
    public function getAllActive(SearchDto $dto): SearchProviderResultInterface
    {
        $provider = new LessonSearchProvider($this->repository);
        $provider->setPagination($dto->offset, $dto->limit);
        return $provider->search();
    }

    /**
     * @param Lesson $lesson
     * @param LessonDto $dto
     * @throws Exception
     */
    public function populate(Lesson $lesson, LessonDto $dto): void
    {
        $lesson->fill($dto->toArray());
    }

    /**
     * @param Lesson $lesson
     */
    public function tryValidate(Lesson $lesson): void
    {
        LessonValidator::validateStatic($lesson);
    }

    /**
     * @param Lesson $lesson
     */
    public function save(Lesson $lesson): void
    {
        $this->tryValidate($lesson);
        $this->repository->save($lesson);
    }

    /**
     * @param Lesson $lesson
     */
    public function destroy(Lesson $lesson): void
    {
        $lesson->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($lesson);
    }

    /**
     * @param Lesson|null $lesson
     */
    protected function ensureExists(?Lesson $lesson): void
    {
        if (!$lesson) {
            throw new NotFoundException(__('app.lesson.errors.notFound'));
        }
    }

    /**
     * @param Lesson $lesson
     * @return Collection
     */
    public function getAllPreviousLessons(Lesson $lesson): Collection
    {
        return $this->repository->findAllActivePrevious($lesson);
    }

    /**
     * @param Lesson $lesson
     * @return Lesson|null
     */
    public function getPreviousLesson(Lesson $lesson): ?Lesson
    {
        return $this->repository->findActivePrevious($lesson);
    }

    /**
     * @param int|null $priority
     * @return Lesson|null
     */
    public function getNextActiveByPriority(?int $priority): ?Lesson
    {
        return $this->repository->findNextActiveByPriority($priority);
    }

}
