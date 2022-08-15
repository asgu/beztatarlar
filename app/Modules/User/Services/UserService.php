<?php


namespace Modules\User\Services;


use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\User\Dto\PasswordResetDto;
use Modules\User\Exceptions\BlockUserException;
use Modules\User\Exceptions\IncorrectLoginException;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Validation\RuleValidation\PasswordValidator;
use Netibackend\Laravel\Exceptions\UnSuccessException;
use Netibackend\Laravel\Helpers\DateHelper;

class UserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return [
            User::ROLE_ADMIN   => 'Администратор',
            User::ROLE_STUDENT => 'Ученик',
        ];
    }

    /**
     * @param string $role
     * @return string
     */
    public function roleLabel(string $role): string
    {
        return self::roles()[$role] ?? $role;
    }

    /**
     * @param User $user
     */
    public function checkAccessAdminPanel(User $user): void
    {
        if (User::ROLE_ADMIN != $user->role) {
            throw new UnSuccessException('Недостаточно прав.');
        }
    }

    /**
     * @param $role
     * @return Collection
     */
    public function getByRole($role): Collection
    {
        return $this->repository->findByRole($role);
    }

    /**
     * @param $email
     * @return User|null
     */
    public function getByEmail($email): ?User
    {
        return $this->repository->findByEmail($email);
    }

    /**
     * @param $email
     * @return Collection
     */
    public function getAllByEmail($email): Collection
    {
        return $this->repository->findAllByEmail($email);
    }

    /**
     * @param $email
     * @return User
     * @throws IncorrectLoginException
     */
    public function tryGetByEmail($email): User
    {
        $user = $this->getByEmail($email);
        $this->ensureExists($user);
        return $user;
    }

    /**
     * @param User $user
     * @param PasswordResetDto $dto
     * @throws Exception
     */
    public function resetPassword(User $user, PasswordResetDto $dto): void
    {
        $this->validatePassword($dto);
        $user->password = $dto->password;
        $this->save($user);
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->repository->save($user);
    }

    /**
     * @param PasswordResetDto $dto
     * @throws Exception
     */
    protected function validatePassword(PasswordResetDto $dto): void
    {
        PasswordValidator::validateStatic($dto->toArray());
    }

    /**
     * @param User|null $user
     * @throws IncorrectLoginException
     */
    protected function ensureExists(?User $user): void
    {
        if (!$user) {
            throw new IncorrectLoginException(['email' => __('user.resetPassword.errors.wrongEmail')]);
        }
    }

    /**
     * @param User $user
     */
    public function tryCheckIsBlocked(User $user)
    {
        if ($this->checkIsBlocked($user)) {
            if ($user->status == User::STATUS_NEED_CONFIRM) {
                throw new BlockUserException(__('user.auth.errors.needConfirm'));
            }
            throw new BlockUserException(__('user.auth.errors.blocked'));
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkIsBlocked(User $user): bool
    {
        return $user->status != ActivityStatusFacade::STATUS_ACTIVE;
    }
}
