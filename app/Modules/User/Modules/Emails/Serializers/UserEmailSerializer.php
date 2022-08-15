<?php


namespace Modules\User\Modules\Emails\Serializers;


use Modules\User\Modules\Emails\Models\UserEmail;
use Netibackend\Laravel\Serializers\AbstractProperties;

class UserEmailSerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            UserEmail::class => [
                'createdAt' => 'created_at',
                'expiredAt' => 'expired_at',
                'email',
                'status',
                'isExpired' => function(UserEmail $request) {
                    return $request->isExpired();
                }
            ],
        ];
    }
}
