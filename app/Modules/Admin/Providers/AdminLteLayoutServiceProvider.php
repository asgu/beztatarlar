<?php

namespace Modules\Admin\Providers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;

class AdminLteLayoutServiceProvider extends BaseServiceProvider
{
    public function boot(Factory $view): void
    {
        $this->registerViewComposers($view);
    }

    private function registerViewComposers(Factory $view): void
    {
        $view->composer('Admin::layout.adminlte.page', AdminLteComposer::class);
    }
}
