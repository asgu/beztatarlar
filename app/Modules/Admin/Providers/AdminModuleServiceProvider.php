<?php

namespace Modules\Admin\Providers;

use Netibackend\Laravel\Providers\BaseModuleServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'Admin';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Admin';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(AdminRouteServiceProvider::class);
    }

    public function boot(): void
    {
        parent::boot();

        $events = app(Dispatcher::class);
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text' => 'Пользователи',
                'icon' => '',
                'url'  => route('user.index'),
            ]);
            $event->menu->add([
                'text'    => 'Справочники',
                'icon'    => 'fas fa-atlas',
                'submenu' => [
                    [
                        'text' => 'Учитель',
                        'url'  => route('teacher.index'),
                    ],
                    [
                        'text' => 'Должность',
                        'url'  => route('position.index'),
                    ],
                    [
                        'text' => 'Мухтасибат',
                        'url'  => route('mukhtasibat.index'),
                    ],
                    [
                        'text' => 'Приход',
                        'url'  => route('parish.index'),
                    ],
                ],
            ]);
            $event->menu->add([
                'text'    => 'Обучение',
                'icon'    => 'fas fa-atlas',
                'submenu' => [
                    [
                        'text' => 'Уроки',
                        'icon' => 'fas fa-fw fa-eye',
                        'url'  => route('lesson.index'),
                    ],
                    [
                        'text' => 'Тесты',
                        'icon' => 'fas fa-fw fa-eye',
                        'url'  => route('test.index'),
                    ]
                ],
            ]);
            $event->menu->add([
                'text' => 'Сертификаты',
                'icon' => 'fas fa-fw fa-eye',
                'url'  => route('certificate.index'),
            ]);
            $event->menu->add([
                'text' => 'Переводы',
                'icon' => 'fas fa-fw fa-eye',
                'url'  => route('languageMessages.index'),
            ]);
            $event->menu->add([
                'text' => 'Api Logger',
                'icon' => '',
                'url'  => route('apiLogger.index'),
            ]);
        });
    }
}
