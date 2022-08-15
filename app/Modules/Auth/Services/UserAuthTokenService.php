<?php


namespace Modules\Auth\Services;

use Exception;
use Illuminate\Support\Str;
use Modules\Auth\Dto\AuthUserDto;
use Modules\Auth\Factories\UserAuthTokenFactory;
use Modules\Auth\Models\UserAuthToken;
use Modules\Auth\Repositories\UserAuthTokenRepository;
use Modules\Auth\Validation\RulesValidation\CreateTokenValidation;
use Modules\User\Models\User;
use Netibackend\Laravel\Exceptions\UnSuccessException;

class UserAuthTokenService
{
    private UserAuthTokenFactory $factory;
    private UserAuthTokenRepository $repository;

    public function __construct(
        UserAuthTokenFactory $factory,
        UserAuthTokenRepository $repository
    )
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param AuthUserDto $dto
     *
     * @return UserAuthToken
     * @throws Exception
     * @throws UnSuccessException
     */
    public function createToken(User $user, AuthUserDto $dto): UserAuthToken
    {
        CreateTokenValidation::validateStatic($dto->toArray());

        $token = $this->factory->createByUser($user, $dto);
        $token->access_token = $this->generateUniqueRandString('access_token');
        $this->repository->save($token);

        return $token;
    }

    /**
     * @param $attr
     *
     * @return string
     */
    public function generateUniqueRandString($attr): string
    {
        do {
            $token = Str::random(64);
        } while (UserAuthToken::query()->where([$attr => $token])->exists());

        return $token;
    }

    /**
     * @param $accessToken
     *
     * @return UserAuthToken|null
     * @throws Exception
     */
    public function getActiveToken($accessToken): ?UserAuthToken
    {
        return $this->repository->getActiveToken($accessToken);
    }

    /**
     * @param UserAuthToken $token
     */
    public function setInactiveToken(UserAuthToken $token): void
    {
        $token->status = UserAuthToken::STATUS_INACTIVE;
        $this->repository->save($token);
    }

    /**
     * @param User $user
     */
    public function terminateAllByUser(User $user): void
    {
        $userAuthTokens = $this->repository->getActiveTokensByUser($user);
        $userAuthTokens->update(['status' => UserAuthToken::STATUS_INACTIVE]);
    }

    public function markAsExpired(): void
    {
        $userAuthTokens = $this->repository->getExpiredTokens();
        $userAuthTokens->update(['status' => UserAuthToken::STATUS_EXPIRED]);
    }

    /**
     * @param User $user
     */
    public function destroyByUser(User $user): void
    {
        $this->repository->deleteAllByUser($user);
    }
}
