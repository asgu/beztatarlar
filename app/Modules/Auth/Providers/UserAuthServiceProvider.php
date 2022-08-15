<?php


namespace Modules\Auth\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;

class UserAuthServiceProvider extends AuthServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::provider('authToken', function($app, array $config) {
            return new UserAuthTokenProvider($config);
        });
    }
}
