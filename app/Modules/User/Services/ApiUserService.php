<?php


namespace Modules\User\Services;


use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Dto\UserFromSocialDTO;
use Modules\Auth\Dto\UserLoginDto;
use Modules\User\Dto\ChangeEmailDto;
use Modules\User\Dto\ChangePasswordDto;
use Modules\User\Dto\RegistrationDto;
use Modules\User\Dto\UserDto;
use Modules\User\Exceptions\IncorrectLoginException;
use Modules\User\Exceptions\IncorrectPasswordException;
use Modules\User\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\User\Modules\Emails\Models\UserEmail;
use Modules\User\Modules\Emails\Services\UserEmailService;
use Modules\User\Modules\Profile\Dto\UserProfileDto;
use Modules\User\Modules\Profile\Helpers\GenderHelper;
use Modules\User\Modules\Profile\Models\UserProfile;
use Modules\User\Modules\Profile\Services\UserProfileService;
use Modules\User\Repositories\UserRepository;
use Modules\User\Validation\RuleValidation\ChangeEmailValidator;
use Modules\User\Validation\RuleValidation\ChangePasswordValidator;
use Modules\User\Validation\RuleValidation\RegistrationValidator;
use Modules\User\Validation\RuleValidation\SocialMediaRegistrationValidator;
use Modules\User\Validation\RuleValidation\UserValidator;
use Netibackend\Laravel\DTO\AbstractDto;
use Netibackend\Laravel\Exceptions\UnSuccessException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class ApiUserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;
    /**
     * @var UserFactory
     */
    private UserFactory $factory;
    /**
     * @var UserProfileService
     */
    private UserProfileService $profileService;
    /**
     * @var UserEmailService
     */
    private UserEmailService $userEmailService;

    public function __construct(
        UserRepository $repository,
        UserFactory $factory,
        UserProfileService $profileService,
        UserEmailService $userEmailService
    )
    {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->profileService = $profileService;
        $this->userEmailService = $userEmailService;
    }

    /**
     * @param string $role
     * @return User
     * @throws Exception
     */
    public function createDraw(string $role): User
    {
        $user = $this->factory->create($role);
        $this->profileService->attachNewToUser($user);
        return $user;
    }

    /**
     * @param RegistrationDto $dto
     * @return User
     * @throws DataValidationException
     * @throws Exception
     */
    public function registrateUser(RegistrationDto $dto): User
    {
        $user = $this->createDraw(User::ROLE_STUDENT);
        $this->populate($user, $dto);
        $this->tryRegistrationValidate($user, $dto);
        $this->save($user);

        return $user;
    }

    /**
     * @param UserFromSocialDTO $dto
     * @return User
     * @throws DataValidationException
     * @throws Exception
     */
    public function registrateFromSocial(UserFromSocialDTO $dto): User
    {
        $user = $this->createDraw(User::ROLE_STUDENT);
        $user->fill($dto->toArray());
        $user->status = User::STATUS_ACTIVE;
        $this->profileService->populateBySocMedia(
            $user->profile,
            UserProfileDto::populateByArray([
                'gender' => GenderHelper::GENDER_UNKNOWN,
                'fullName' => $dto->name
            ])
        );

        $this->tryValidateSocialMedia($user);
        $this->save($user);

        return $user;
    }

    /**
     * @param $hash
     * @throws Exception
     */
    public function confirmRegistrationEmail($hash): void
    {
        $confirm = $this->userEmailService->tryGetRegistrationEmailByHash($hash);
        $this->userEmailService->confirm($confirm);
        $this->activateUser($confirm->user);
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function confirmUser(User $user): void
    {
        $confirm = $this->userEmailService->getRegistrationEmailByUser($user);
        if ($confirm) {
            $this->userEmailService->confirm($confirm, $forced = true);
        }
        $this->activateUser($user);
    }

    /**
     * @param $hash
     * @throws Exception
     */
    public function confirmChangeEmail($hash): void
    {
        $confirm = $this->userEmailService->tryGetChangeEmailByHash($hash);
        $this->userEmailService->confirm($confirm);
        $this->changeEmailByConfirm($confirm);
    }

    /**
     * @param UserLoginDto $dto
     * @return User
     * @throws IncorrectLoginException
     * @throws IncorrectPasswordException
     */
    public function getByCredentials(UserLoginDto $dto): User
    {
        $user = $this->repository->findByEmail($dto->email);
        $this->ensureExists($user);
        $this->ensureCorrectStatus($user);
        $this->ensureCorrectPassword($user, $dto->password);
        return $user;
    }

    /**
     * @param User|null $user
     * @throws IncorrectLoginException
     */
    protected function ensureExists(?User $user): void
    {
        if (!$user) {
            throw new IncorrectLoginException(['email' => __('user.auth.errors.wrongEmail')]);
        }
    }

    /**
     * @param User $user
     */
    protected function ensureCorrectStatus(User $user): void
    {
        if (!in_array($user->status, [User::STATUS_ACTIVE, User::STATUS_NEED_CONFIRM])) {
            throw new UnSuccessException(__('user.auth.errors.incorrectStatus'));
        }
    }

    /**
     * @param User $user
     * @param $password
     * @param string $passwordAttribute
     * @throws IncorrectPasswordException
     */
    protected function ensureCorrectPassword(User $user, $password, string $passwordAttribute = 'password'): void
    {
        if (!Hash::check($password, $user->password)) {
            throw new IncorrectPasswordException([$passwordAttribute => __('user.auth.errors.incorrectPassword')]);
        }
    }

    /**
     * @param User $user
     * @throws Exception
     */
    protected function activateUser(User $user): void
    {
        $user->status = User::STATUS_ACTIVE;
        $this->save($user);
    }

    /**
     * @param User $user
     * @param UserDto $dto
     * @throws Exception
     */
    protected function populate(User $user, UserDto $dto): void
    {
        $user->fill($dto->toArray());
        $user->mukhtasibat_id = $dto->mukhtasibatId;
        $user->position_id = $dto->positionId;
        $user->parish_id = $dto->parishId;
        $this->profileService->populate($user->profile, $dto->getProfile());
    }

    /**
     * @param User $user
     * @param RegistrationDto $dto
     * @throws DataValidationException
     * @throws Exception
     */
    protected function tryRegistrationValidate(User $user, RegistrationDto $dto): void
    {
        RegistrationValidator::validateStatic($dto->toArray());
        $this->profileService->tryValidate($user->profile);
    }

    /**
     * @param User $user
     * @throws DataValidationException
     */
    protected function tryValidateSocialMedia(User $user): void
    {
        SocialMediaRegistrationValidator::validateStatic($user);
        $this->profileService->tryValidateSocialMedia($user->profile);
    }

    /**
     * @param User $user
     * @throws Exception
     */
    protected function save(User $user): void
    {
        $profile = $user->profile;
        $this->repository->save($user);
        $this->profileService->attachToUser($user, $profile);
    }

    /**
     * @param User $user
     * @param UserDto $dto
     * @throws Exception
     */
    public function update(User $user, UserDto $dto): void
    {
        $this->populate($user, $dto);
        $this->tryUpdateValidate($user);
        $this->save($user);
    }

    /**
     * @param User $user
     * @throws DataValidationException
     */
    private function tryUpdateValidate(User $user): void
    {
        UserValidator::validateStatic($user);
        $this->profileService->tryValidate($user->profile);
    }

    /**
     * @param User $user
     * @param ChangeEmailDto $dto
     * @throws IncorrectPasswordException
     * @throws Exception
     */
    public function ensureCanChangeEmail(User $user, ChangeEmailDto $dto)
    {
        $this->ensureCorrectPassword($user, $dto->password);
        $this->tryValidateChangeEmail($user, $dto);
    }

    /**
     * @param User $user
     * @param ChangePasswordDto $dto
     * @throws IncorrectPasswordException
     */
    public function changePassword(User $user, ChangePasswordDto $dto)
    {
        $this->ensureCorrectPassword($user, $dto->currentPassword, 'currentPassword');
        $this->tryValidateChangePassword($dto);
        $user->password = $dto->newPassword;
        $this->save($user);
    }

    /**
     * @param User $user
     * @param ChangeEmailDto $dto
     * @throws DataValidationException
     * @throws Exception
     */
    private function tryValidateChangeEmail(User $user, ChangeEmailDto $dto)
    {
        $validator = new ChangeEmailValidator($user->id);
        $validator->validate($dto->toArray());
    }

    /**
     * @param UserEmail $confirm
     * @throws Exception
     */
    protected function changeEmailByConfirm(UserEmail $confirm): void
    {
        $user = $confirm->user;
        $user->email = $confirm->email;
        $this->save($user);
    }

    private function tryValidateChangePassword(ChangePasswordDto $dto)
    {
        ChangePasswordValidator::validateStatic($dto->toArray());
    }
}
