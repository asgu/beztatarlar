<?php


namespace Modules\Auth\Contracts;


use Illuminate\Contracts\Auth\Authenticatable;

interface UserAuthTokenStorageInterface
{
    /**
     * @param mixed $token
     *
     * @return Authenticatable|null
     */
    public function getAuthenticatableByToken($token): ?Authenticatable;
}
