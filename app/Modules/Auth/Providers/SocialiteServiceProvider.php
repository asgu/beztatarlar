<?php


namespace Modules\Auth\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class SocialiteServiceProvider extends EventServiceProvider
{
    protected $listen = [
        SocialiteWasCalled::class => [
            'SocialiteProviders\\VKontakte\\VKontakteExtendSocialite@handle',
            'SocialiteProviders\\Odnoklassniki\\OdnoklassnikiExtendSocialite@handle',
            'SocialiteProviders\\Apple\\AppleExtendSocialite@handle',
        ],
    ];

}
