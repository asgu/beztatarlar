<?php


namespace Modules\Teacher\Services;


use App\Api\Dto\SearchDto;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Teacher\Dto\TeacherDto;
use Modules\Teacher\Factories\TeacherFactory;
use Modules\Teacher\Models\Teacher;
use Modules\Teacher\Repositories\TeacherRepository;
use Modules\Teacher\Search\TeacherSearchOffsetProvider;
use Modules\Teacher\Validation\RuleValidation\TeacherValidator;
use Neti\Laravel\Files\Services\FileService;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\SearchProvider\Interfaces\SearchProviderResultInterface;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class TeacherService
{
    /**
     * @var TeacherRepository
     */
    private TeacherRepository $repository;
    /**
     * @var TeacherFactory
     */
    private TeacherFactory $factory;
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct(TeacherRepository $repository, TeacherFactory $factory, FileService $fileService)
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->fileService = $fileService;
    }

    /**
     * @return Teacher
     */
    public function createDraw(): Teacher
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return Teacher|null
     */
    public function getById($id): ?Teacher
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return Teacher
     */
    public function tryGetById($id): Teacher
    {
        $teacher = $this->getById($id);
        $this->ensureExists($teacher);
        return $teacher;
    }

    /**
     * @param SearchDto $dto
     * @return SearchProviderResultInterface
     * @throws DataValidationException
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function getList(SearchDto $dto): SearchProviderResultInterface
    {
        $provider = new TeacherSearchOffsetProvider();
        $provider->setPagination($dto->offset, $dto->limit);
        return $provider->search($dto->toArray());
    }

    /**
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->getQuery())
            ->filterColumn('status', function (Builder $query, $status) {
                $query->where('status', $status);
            })
            ->filterColumn('description', function (Builder $query, $description) {
                $query->where('description', 'like', "%$description%");
            });
    }

    /**
     * @param Teacher $teacher
     * @param TeacherDto $dto
     * @throws Exception
     */
    public function populate(Teacher $teacher, TeacherDto $dto): void
    {
        $teacher->fill($dto->toArray());
        $this->setPhoto($teacher, $dto->photo);
    }

    /**
     * @param Teacher $teacher
     * @throws DataValidationException
     */
    public function tryValidate(Teacher $teacher): void
    {
        TeacherValidator::validateStatic($teacher, true);
    }

    /**
     * @param Teacher $teacher
     * @throws Exception
     */
    public function save(Teacher $teacher): void
    {
        $this->savePhoto($teacher);
        $this->repository->save($teacher);
    }

    /**
     * @param Teacher $teacher
     * @throws Exception
     */
    public function destroy(Teacher $teacher): void
    {
        $teacher->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($teacher);
    }

    /**
     * @param Teacher $teacher
     * @param UploadedFile|null $file
     * @throws Exception
     */
    protected function setPhoto(Teacher $teacher, ?UploadedFile $file): void
    {
        if ($file) {
            $photo = $this->fileService->createDrawByUploadedFile($file);
            $teacher->setPhoto($photo);
        }
    }

    /**
     * @param Teacher $teacher
     * @throws Exception
     */
    protected function savePhoto(Teacher $teacher): void
    {
        if ($teacher->photo && $teacher->photo->needSaveFile) {
            $this->fileService->save($teacher->photo);
            $teacher->photo_uuid = $teacher->photo->uuid;
        }

        if ($teacher->removePhoto) {
            $this->fileService->safeRemove($teacher->removePhoto);
        }
    }

    /**
     * @param Teacher|null $teacher
     */
    protected function ensureExists(?Teacher $teacher): void
    {
        if (!$teacher) {
            throw new NotFoundException(__('app.teacher.errors.notFound'));
        }
    }
}
