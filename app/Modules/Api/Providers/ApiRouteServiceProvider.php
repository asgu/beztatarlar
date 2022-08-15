<?php

namespace Modules\Api\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class ApiRouteServiceProvider extends RouteServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected string $moduleNamespace = 'Modules\Api\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        Route::middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(base_path('Modules/Api/Routes/api.php'));
    }
}
