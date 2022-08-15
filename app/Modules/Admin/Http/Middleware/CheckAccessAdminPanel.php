<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;
use Modules\User\Services\UserService;
use Netibackend\Laravel\Exceptions\UnSuccessException;

class CheckAccessAdminPanel
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    public function __construct(
        UserService $userService,
    )
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null): mixed
    {
        if (Auth::guard($guard)->guest()) {
            throw new UnSuccessException(
                'Unauthenticated.'
            );
        }

        /** @var User $user */
        $user = Auth::guard($guard)->user();
        $this->userService->checkAccessAdminPanel($user);

        return $next($request);
    }
}
