<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getCurrentUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationException();
        }

        return $user;
    }
}
