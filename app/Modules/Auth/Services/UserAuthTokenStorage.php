<?php


namespace Modules\Auth\Services;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Auth\Contracts\UserAuthTokenStorageInterface;

class UserAuthTokenStorage implements UserAuthTokenStorageInterface
{
    /**
     * @var UserAuthTokenService
     */
    protected UserAuthTokenService $userAuthTokenService;

    public function __construct(
        UserAuthTokenService $userAuthTokenService
    )
    {
        $this->userAuthTokenService = $userAuthTokenService;
    }

    /**
     * @param mixed $token
     *
     * @return Authenticatable|null
     * @throws Exception
     */
    public function getAuthenticatableByToken($token): ?Authenticatable
    {
        $authToken = $this->userAuthTokenService->getActiveToken($token);
        return $authToken->user ?? null;
    }
}
