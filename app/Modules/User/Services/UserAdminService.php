<?php

namespace Modules\User\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Services\UserAuthTokenService;
use Modules\Certificate\Models\Certificate;
use Modules\Certificate\Services\CertificateService;
use Modules\User\Models\User;
use Modules\User\Modules\Emails\Services\UserEmailService;
use Modules\User\Modules\Profile\Services\UserProfileService;
use Modules\User\Repositories\UserRepository;
use Modules\UserProgress\Modules\Lesson\Models\LessonUserProgress;
use Modules\UserProgress\Modules\Topic\Models\TopicUserProgress;
use Modules\UserTestResult\Models\UserTestResult;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Modules\ApiLogger\Models\ApiLogger;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class UserAdminService
{
    protected UserRepository $repository;
    /**
     * @var UserProfileService
     */
    private UserProfileService $profileService;
    /**
     * @var UserAuthTokenService
     */
    private UserAuthTokenService $authTokenService;
    /**
     * @var UserEmailService
     */
    private UserEmailService $userEmailService;
    /**
     * @var CertificateService
     */
    private CertificateService $certificateService;

    public function __construct(
        UserRepository $repository,
        UserProfileService $profileService,
        UserAuthTokenService $authTokenService,
        UserEmailService $userEmailService,
        CertificateService $certificateService
    ) {
        $this->repository = $repository;
        $this->profileService = $profileService;
        $this->authTokenService = $authTokenService;
        $this->userEmailService = $userEmailService;
        $this->certificateService = $certificateService;
    }

    /**
     * @param $id
     *
     * @return User
     * @throws NotFoundException
     */
    public function getTryById($id): User
    {
        $model = $this->repository->getById($id);

        if (!$model) {
            throw new NotFoundException('Пользователь не существует');
        }

        return $model;
    }

    /**
     * @return Builder
     */
    public function getAdminQuery(): Builder
    {
        return $this->repository->getAdminQuery();
    }

    /**
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(): EloquentDatatable
    {
        return DataTables::eloquent($this->getAdminQuery())
            ->filterColumn('profile.surname', function (Builder $query, $surname) {
                $userIds = $this->profileService->getBySurname($surname)->pluck('user_id')->toArray();
                $query->whereIn('users.id', $userIds);
            })
            ->filterColumn('profile.name', function (Builder $query, $name) {
                $userIds = $this->profileService->getByName($name)->pluck('user_id')->toArray();
                $query->whereIn('users.id', $userIds);
            })
            ->filterColumn('profile.patronymic', function (Builder $query, $patronymic) {
                $userIds = $this->profileService->getByPatronymic($patronymic)->pluck('user_id')->toArray();
                $query->whereIn('users.id', $userIds);
            })
            ->filterColumn('role', function (Builder $query, $role) {
                $query->where('users.role', $role);
            });
    }

    /**
     * @param $id
     */
    public function destroyById($id): void
    {
        $user = $this->repository->getById($id);
        if ($user) {
            try {
                $this->profileService->destroy($user->profile);
            } catch (\Throwable $e) {}

            $this->authTokenService->destroyByUser($user);
            $this->userEmailService->deleteByUser($user);
            $this->certificateService->destroyByUser($user);
            ApiLogger::query()
                ->where('user_id', $user->id)
                ->delete();

            TopicUserProgress::query()
                ->where('user_id', $user->id)
                ->delete();
            LessonUserProgress::query()
                ->where('user_id', $user->id)
                ->delete();
            UserTestResult::query()
                ->where('user_id', $user->id)
                ->delete();
            $user->delete();
        }
    }
}
