<?php


namespace Modules\User\Serializers;


use Modules\Mukhtasibat\Models\Mukhtasibat;
use Modules\Mukhtasibat\Serializers\MukhtasibatSerializer;
use Modules\Parish\Models\Parish;
use Modules\Parish\Serializers\ParishSerializer;
use Modules\Position\Models\Position;
use Modules\Position\Serializers\PositionSerializer;
use Modules\User\Models\User;
use Modules\User\Modules\Profile\Serializers\UserProfileSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            User::class => [
                'id',
                'email',
                'mukhtasibat',
                'position',
                'parish',
                'profile'
            ],
            Mukhtasibat::class => MukhtasibatSerializer::class,
            Position::class => PositionSerializer::class,
            Parish::class => ParishSerializer::class,
            Profile::class => UserProfileSerializer::class
        ];
    }
}
