<?php

namespace App\Translation;

class TranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    protected function registerLoader(): void
    {
        $this->app->singleton('translation.loader', function($app) {
            return new DatabaseLoader($app['files'], $app['path.lang']);
        });
    }
}
