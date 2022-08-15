<?php


namespace Modules\Auth\Serializers;

use Modules\Auth\Models\UserAuthToken;
use Netibackend\Laravel\Serializers\AbstractProperties;

class UserAuthTokenSerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            UserAuthToken::class => [
                'type',
                'accessToken' => 'access_token',
            ]
        ];
    }
}
