<?php


namespace Modules\Certificate\Services;


use App\Helpers\DateHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Certificate\Dto\CertificateDto;
use Modules\Certificate\Factories\CertificateFactory;
use Modules\Certificate\Models\Certificate;
use Modules\Certificate\Repositories\CertificateRepository;
use Modules\Certificate\Validation\RulesValidation\CertificateValidator;
use Modules\User\Models\User;
use Modules\User\Services\UserService;
use Neti\Laravel\Files\Services\FileService;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class CertificateService
{
    /**
     * @var CertificateRepository
     */
    private CertificateRepository $repository;
    /**
     * @var CertificateFactory
     */
    private CertificateFactory $factory;
    /**
     * @var UserService
     */
    private UserService $userService;
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct(
        CertificateRepository $repository,
        CertificateFactory $factory,
        UserService $userService,
        FileService $fileService
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->userService = $userService;
        $this->fileService = $fileService;
    }

    /**
     * @param Certificate $certificate
     * @return bool
     */
    public function isActive(Certificate $certificate): bool
    {
        return true;
    }

    /**
     * @param User $createdByUser
     * @return Certificate
     * @throws Exception
     */
    public function createDraw(User $createdByUser): Certificate
    {
        return $this->factory->create($createdByUser);
    }

    /**
     * @return Certificate|null
     */
    public function getActive(): ?string
    {
        $path = storage_path('certificate');
        $template = $path . '/certificate.pdf';
        if (file_exists($template)) {
            return $template;
        }
        return null;
//        return $this->repository->findLatest();
    }

    /**
     * @return Certificate
     */
    public function tryGetActive(): Certificate
    {
        $certificate = $this->getActive();
        $this->ensureExists($certificate);
        return $certificate;
    }

    /**
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->getQuery())
            ->filterColumn('createdByUser', function (Builder $query, $login) {
                $ids = $this->userService->getAllByEmail($login)->pluck('id')->all();
                $query->whereIn('user_id', $ids);
            })
            ->filterColumn('created_at', function (Builder $query, $date) {
                $formattedDate = explode('.', $date);
                $formattedDate = array_reverse($formattedDate);
                $formattedDate = implode('-', $formattedDate);
                $query->where('created_at', 'LIKE', "%$formattedDate%");
            });
    }

    /**
     * @param Certificate $certificate
     * @param CertificateDto $dto
     * @throws Exception
     */
    public function populate(Certificate $certificate, CertificateDto $dto): void
    {
        if ($dto->file) {
            $file = $this->fileService->createDrawByUploadedFile($dto->file);
            $certificate->setFile($file);
        }
    }

    /**
     * @param Certificate $certificate
     * @throws DataValidationException
     */
    public function tryValidate(Certificate $certificate): void
    {
        CertificateValidator::validateStatic($certificate, true);
    }

    /**
     * @param Certificate $certificate
     * @throws Exception
     */
    public function save(Certificate $certificate): void
    {
        $this->tryValidate($certificate);
        $this->saveFile($certificate);
        $this->repository->save($certificate);
    }

    /**
     * @param Certificate $certificate
     * @throws Exception
     */
    protected function saveFile(Certificate $certificate): void
    {
        if ($certificate->file && $certificate->file->needSaveFile) {
            $this->fileService->save($certificate->file);
            $certificate->file_uuid = $certificate->file->uuid;
        }

        if ($certificate->removeFile) {
            $this->fileService->safeRemove($certificate->removeFile);
        }
    }

    /**
     * @param Certificate|null $certificate
     */
    protected function ensureExists(?Certificate $certificate): void
    {
        if (!$certificate) {
            throw new NotFoundException(__('app.certificate.errors.notFound'));
        }
    }

    /**
     * @param $id
     * @return Certificate|null
     */
    public function tryGetById($id): ?Certificate
    {
        $certificate = $this->getById($id);
        $this->ensureExists($certificate);
        return $certificate;
    }

    /**
     * @param $id
     * @return Certificate|null
     */
    public function getById($id): ?Certificate
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function destroyById($id): void
    {
        $certificate = $this->getById($id);
        if ($certificate) {
            $this->fileService->safeRemove($certificate->file);
            $this->repository->delete($certificate);
        }
    }

    public function destroyByUser(User $user)
    {
        $this->repository->deleteByUser($user);
    }

}
