<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @return Factory|View|Application
     */
    public function showLoginForm(): Factory|View|Application
    {
        return view('Admin::auth.login');
    }

    /**
     * @return string
     */
    public function redirectTo(): string
    {
        return route('user.index');
    }
}
