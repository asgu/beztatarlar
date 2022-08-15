<?php

namespace App\Api\Routing;

use Illuminate\Auth\AuthenticationException;
use Modules\User\Models\User;
use Netibackend\Laravel\Api\Routing\BaseApiController as NetiBaseApiController;

class BaseApiController extends NetiBaseApiController
{
    /**
     * @return User
     * @throws AuthenticationException
     */
    public function getCurrentUser(): User
    {
        $user = request()->user();

        if (!$user) {
            throw new AuthenticationException();
        }

        return $user;
    }
}
