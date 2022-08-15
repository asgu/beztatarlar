<?php


namespace Modules\User\Modules\Emails\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Email\Services\EmailService;
use Modules\User\Dto\ChangeEmailDto;
use Modules\User\Models\User;
use Modules\User\Modules\Emails\Factories\UserEmailFactory;
use Modules\User\Modules\Emails\Models\UserEmail;
use Modules\User\Modules\Emails\Repositories\UserEmailRepository;
use Modules\User\Modules\Emails\Validation\RulesValidation\UserEmailValidation;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Exceptions\UnSuccessException;
use Netibackend\Laravel\Helpers\DateHelper;

class UserEmailService
{
    private UserEmailFactory $factory;
    private UserEmailRepository $repository;
    private EmailService $emailService;

    public function __construct(
        UserEmailFactory $factory,
        UserEmailRepository $repository,
        EmailService $emailService
    )
    {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->emailService = $emailService;
    }

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function sendRegistrationConfirm(User $user): void
    {
        if ($user->email) {
            $userEmail = $this->createRequestByUser($user, $user->email, UserEmail::TYPE_REGISTRATION);
            $this->emailService->sendRegistrationMail($userEmail);
        }
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function sendPasswordResetToken(User $user): void
    {
        if ($user->email) {
            $userEmail = $this->createRequestByUser($user, $user->email, UserEmail::TYPE_PASSWORD_RESET);
            $this->emailService->sendPasswordResetMail($userEmail);
        }
    }

    /**
     * @param User $user
     * @param ChangeEmailDto $dto
     * @throws Exception
     */
    public function sendChangeEmailConfirm(User $user, ChangeEmailDto $dto): void
    {
        if ($dto->email) {
            $userEmail = $this->createRequestByUser($user, $dto->email, UserEmail::TYPE_CHANGE_EMAIL);
            $userEmail->expired_at = DateHelper::getModifyDate($userEmail->created_at, '+1 day');
            $this->emailService->sendChangeEmailMail($userEmail);
        }
    }

    /**
     * @param User $user
     * @param string $email
     * @param string $type
     * @return UserEmail
     * @throws Exception
     */
    private function createRequestByUser(User $user, string $email, string $type): UserEmail
    {
        $userEmail = $this->factory->create($type);

        $userEmail->hash = $this->generateHash();
        $userEmail->expired_at = DateHelper::getModifyDate($userEmail->created_at, '+1 hour');
        $userEmail->email = $email;
        $userEmail->user_id = $user->id;

        $this->repository->setStatusForAll(
            UserEmail::STATUS_EXPIRED,
            [
                'user_id' => $userEmail->user_id,
                'type' => $userEmail->type,
                'email' => $userEmail->email
            ]
        );
        $this->repository->save($userEmail);
        return $userEmail;
    }

    /**
     * @param $hash
     * @return UserEmail
     */
    public function tryGetRegistrationEmailByHash($hash): UserEmail
    {
        $model = $this->repository->getUserEmailByTypeAndHash(UserEmail::TYPE_REGISTRATION, $hash);
        $this->ensureExists($model);
        $this->ensureExists($model);
        return $model;
    }

    /**
     * @param $hash
     * @return UserEmail
     * @throws Exception
     */
    public function tryGetPasswordResetEmailByHash($hash): UserEmail
    {
        $model = $this->repository->getUserEmailByTypeAndHash(UserEmail::TYPE_PASSWORD_RESET, $hash);
        $this->ensureExists($model);
        $this->ensureNotExpired($model);
        return $model;
    }

    /**
     * @param $hash
     * @return UserEmail
     * @throws Exception
     */
    public function tryGetChangeEmailByHash($hash): UserEmail
    {
        $model = $this->repository->getUserEmailByTypeAndHash(UserEmail::TYPE_CHANGE_EMAIL, $hash);
        $this->ensureExists($model);
        $this->ensureNotExpired($model);
        return $model;
    }

    /**
     * @param UserEmail|null $userEmail
     */
    protected function ensureExists(?UserEmail $userEmail): void
    {
        if (!$userEmail) {
            throw new NotFoundException(__('app.api.confirm.hashNotFound'));
        }
    }

    /**
     * @param UserEmail $userEmail
     *
     * @throws Exception
     * @throws UnSuccessException
     */
    private function ensureNotExpired(UserEmail $userEmail): void
    {
        if (!$userEmail->isSent() || $userEmail->isExpired()) {
            throw new UnSuccessException(__('app.api.confirm.hashExpired'));
        }
    }

    /**
     * @param UserEmail $userEmail
     * @param bool $forced
     * @throws Exception
     */
    public function confirm(UserEmail $userEmail, bool $forced = false): void
    {
        if (!$forced) {
            $this->ensureNotExpired($userEmail);
        }

        $userEmail->status = UserEmail::STATUS_CONFIRM;
        $this->repository->save($userEmail);
    }

    /**
     * @return string
     */
    private function generateHash(): string
    {
        do {
            $token = Str::random(64);
        } while (UserEmail::query()->where(['hash' => $token])->exists());

        return $token;
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function reSendRegistrationConfirm(User $user): void
    {
        $this->ensureNotConfirmed($user);
        $this->setExpiredAllRegistration($user);
        $this->sendRegistrationConfirm($user);
    }

    /**
     * @param User $user
     * @return Builder|Model|UserEmail|null
     */
    public function getRegistrationEmailByUser(User $user): ?UserEmail
    {
        return $this->repository->getRegistrationEmailByUser($user);
    }

    /**
     * @param User $user
     */
    public function deleteByUser(User $user): void
    {
        $this->repository->deleteAllByUser($user);
    }

    /**
     * @param mixed $email
     * @param int $userId
     */
    private function tryValidateData(mixed $email, int $userId): void
    {
        UserEmailValidation::validateStatic(['email' => $email, 'user_id' => $userId]);
    }

    /**
     * @param User $user
     */
    private function setExpiredAllRegistration(User $user)
    {
        $this->repository->setExpiredAll($user, UserEmail::TYPE_REGISTRATION);
    }

    /**
     * @param User $user
     */
    private function ensureNotConfirmed(User $user)
    {
        if ($user->status == User::STATUS_ACTIVE) {
            throw new UnSuccessException(__('user.email.alreadyConfirmed'));
        }
    }
}
