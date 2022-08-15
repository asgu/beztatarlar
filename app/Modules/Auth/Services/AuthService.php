<?php


namespace Modules\Auth\Services;

use Exception;
use Modules\Auth\Dto\AuthUserDto;
use Modules\Auth\Models\UserAuthToken;
use Modules\User\Models\User;

class AuthService
{
    private UserAuthTokenService $userAuthTokenService;

    public function __construct(UserAuthTokenService $userAuthTokenService)
    {
        $this->userAuthTokenService = $userAuthTokenService;
    }

    /**
     * @param User $user
     * @param AuthUserDto $dto
     *
     * @return UserAuthToken
     * @throws Exception
     */
    public function createToken(User $user, AuthUserDto $dto): UserAuthToken
    {
        return $this->userAuthTokenService->createToken($user, $dto);
    }

    /**
     * @param User $user
     */
    public function logoutFromAllDevices(User $user): void
    {
        $this->userAuthTokenService->terminateAllByUser($user);
    }

    /**
     * @param $accessToken
     *
     * @return bool
     * @throws Exception
     */
    public function logoutByAccessToken($accessToken): bool
    {
        $token = $this->userAuthTokenService->getActiveToken($accessToken);

        if ($token) {
            $this->userAuthTokenService->setInactiveToken($token);
            return true;
        }

        return false;
    }
}
