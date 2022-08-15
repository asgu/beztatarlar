<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class AdminRouteServiceProvider extends RouteServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected string $moduleNamespace = 'Modules\Admin\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(base_path('Modules/Admin/Routes/web.php'));
    }
}
