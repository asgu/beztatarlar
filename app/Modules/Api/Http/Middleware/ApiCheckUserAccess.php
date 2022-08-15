<?php

namespace Modules\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\User\Services\UserService;

class ApiCheckUserAccess
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User $user */
        if ($user = $request->user()) {
            $this->userService->tryCheckIsBlocked($user);
        }

        return $next($request);
    }
}
