<?php


namespace Modules\User\Modules\Profile\Services;


use App\Helpers\DateHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Modules\File\Services\FileService;
use Modules\User\Models\User;
use Modules\User\Modules\Profile\Dto\UserProfileDto;
use Modules\User\Modules\Profile\Facroties\UserProfileFactory;
use Modules\User\Modules\Profile\Models\UserProfile;
use Modules\User\Modules\Profile\Repositories\UserProfileRepository;
use Modules\User\Modules\Profile\Validation\RulesValidation\SocialMediaProfileValidator;
use Modules\User\Modules\Profile\Validation\RulesValidation\UserProfileValidator;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Exceptions\UnSuccessException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class UserProfileService
{
    /**
     * @var UserProfileFactory
     */
    private UserProfileFactory $factory;
    /**
     * @var UserProfileRepository
     */
    private UserProfileRepository $repository;
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct(
        UserProfileFactory $factory,
        UserProfileRepository $repository,
        FileService $fileService
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->fileService = $fileService;
    }

    /**
     * @return UserProfile
     */
    #[Pure]
    public function createDraw(): UserProfile
    {
        return $this->factory->create();
    }

    /**
     * @param $value
     * @return Collection
     */
    public function getBySurname($value): Collection
    {
        return $this->repository->findBySurname($value);
    }

    /**
     * @param $value
     * @return Collection
     */
    public function getByName($value): Collection
    {
        return $this->repository->findByName($value);
    }

    /**
     * @param $value
     * @return Collection
     */
    public function getByPatronymic($value): Collection
    {
        return $this->repository->findByPatronymic($value);
    }

    /**
     * @param User $user
     */
    public function attachNewToUser(User $user): void
    {
        $profile = $this->createDraw();
        $user->setRelation('profile', $profile);
    }

    /**
     * @param User $user
     * @param UserProfile $profile
     * @throws Exception
     */
    public function attachToUser(User $user, UserProfile $profile): void
    {
        $profile->user_id = $user->id;
        $this->save($profile);
    }

    /**
     * @param UserProfile $profile
     * @param UserProfileDto $dto
     * @throws Exception
     */
    public function populate(UserProfile $profile, ?UserProfileDto $dto): void
    {
        if (!$dto) {
            throw new UnSuccessException(__('user.profile.errors.notFilled'));
        }
        $profile->fill($dto->toArray());
        $this->parseFullName($profile, $dto);
        $this->setPhoto($profile, $dto->photo);
    }

    /**
     * @param UserProfile $profile
     * @param UserProfileDto|null $dto
     * @throws Exception
     */
    public function populateBySocMedia(UserProfile $profile, ?UserProfileDto $dto): void
    {
        $this->populate($profile, $dto);
        //соц сети возвращают фамилию и имя в обратном порядке
        $surname = $profile->name;
        $profile->name = $profile->surname;
        $profile->surname = $surname;
    }

    /**
     * @param UserProfile $profile
     * @param UserProfileDto $dto
     */
    protected function parseFullName(UserProfile $profile, UserProfileDto $dto): void
    {
        if ($dto->fullName) {
            $fullName = $this->removeSpaces($dto->fullName);
            $names = explode(' ', $fullName);
            $profile->surname = $names[0];
            $profile->name = isset($names[1]) ? $names[1] : null;
            $profile->patronymic = isset($names[2]) ? $names[2] : null;
        }
    }

    /**
     * @param string $str
     * @return string
     */
    protected function removeSpaces(string $str): string
    {
        $newStr = str_replace('  ', ' ', $str);
        if (strpos($newStr, '  ') !== false) {
            return $this->removeSpaces($newStr);
        }
        return $newStr;
    }

    /**
     * @param UserProfile $profile
     * @throws DataValidationException
     */
    public function tryValidate(UserProfile $profile): void
    {
        UserProfileValidator::validateStatic($profile, true);
    }

    /**
     * @param UserProfile $profile
     */
    public function tryValidateSocialMedia(UserProfile $profile): void
    {
        SocialMediaProfileValidator::validateStatic($profile);
    }

    /**
     * @param UserProfile $profile
     * @throws Exception
     */
    public function save(UserProfile $profile): void
    {
        $this->savePhoto($profile);
        $this->saveCertificate($profile);
        $this->repository->save($profile);
    }

    /**
     * @param UserProfile $profile
     * @param File $certificate
     * @throws Exception
     */
    public function attachCertificate(UserProfile $profile, File $certificate): void
    {
        $this->ensureCanAttachCertificate($profile);
        $profile->setCertificate($certificate);

        $this->save($profile);
    }

    /**
     * @param UserProfile $profile
     * @param UploadedFile|null $file
     * @throws Exception
     */
    protected function setPhoto(UserProfile $profile, ?UploadedFile $file): void
    {
        if ($file) {
            $photo = $this->fileService->createDrawByUploadedFile($file);
            $profile->setPhoto($photo);
        }
    }

    /**
     * @param UserProfile $profile
     * @throws Exception
     */
    protected function savePhoto(UserProfile $profile): void
    {
        if ($profile->photo && $profile->photo->needSaveFile) {
            $this->fileService->save($profile->photo);
            $profile->photo_uuid = $profile->photo->uuid;
        }

        if ($profile->removePhoto) {
            $this->fileService->safeRemove($profile->removePhoto);
        }
    }

    /**
     * @param UserProfile $profile
     * @throws Exception
     */
    private function saveCertificate(UserProfile $profile)
    {
        if ($profile->certificate && $profile->certificate->needSaveFile) {
            $this->fileService->save($profile->certificate);
            $profile->certificate_uuid = $profile->certificate->uuid;
        }

        if ($profile->removeCertificate) {
            $this->fileService->safeRemove($profile->removeCertificate);
        }
    }

    /**
     * @param UserProfile $profile
     */
    protected function ensureCanAttachCertificate(UserProfile $profile): void
    {
        if ($profile->certificate) {
            throw new UnSuccessException(__('user.profile.errors.certificateExists'));
        }
    }

    /**
     * @param UserProfile $profile
     */
    public function destroy(UserProfile $profile): void
    {
        $this->repository->delete($profile);
    }

    /**
     * @return EloquentDatatable
     */
    public function getCertificatesFilteredDataTable(): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->certificatesQuery())
            ->filterColumn('certificate.created_at', function (Builder $query, $date) {
                $query->whereHas('certificate', function (Builder $subquery) use ($date) {
                    $dateFrom = DateHelper::formatDate($date, 'Y-m-d 00:00:00');
                    $dateTo = DateHelper::formatDate($date, 'Y-m-d 23:59:59');
                    $subquery
                        ->where('files.created_at', '>=', $dateFrom)
                        ->where('files.created_at', '<=', $dateTo);
                });
            })
            ->filterColumn('fio', function (Builder $query, $name) {
                $query
                    ->orWhere('name', 'like', "%{$name}%")
                    ->orWhere('surname', 'like', "%{$name}%")
                    ->orWhere('patronymic', 'like', "%{$name}%");
            })
            ->orderColumn('fio', function (Builder $query, $sort) {
                $query
                    ->orderBy('surname', $sort)
                    ->orderBy('name', $sort)
                    ->orderBy('patronymic', $sort);
            });
    }

    /**
     * @param $id
     * @return UserProfile
     */
    public function tryGetById($id): UserProfile
    {
        $profile = $this->getById($id);
        $this->ensureExists($profile);
        return $profile;
    }

    /**
     * @param $id
     * @return UserProfile|null
     */
    private function getById($id): ?UserProfile
    {
        return $this->repository->findById($id);
    }

    /**
     * @param UserProfile|null $profile
     */
    private function ensureExists(?UserProfile $profile)
    {
        if (!$profile) {
            throw new UnSuccessException(__('user.profile.errors.notExists'));
        }
    }
}
