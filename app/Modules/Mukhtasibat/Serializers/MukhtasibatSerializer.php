<?php


namespace Modules\Mukhtasibat\Serializers;


use Modules\Mukhtasibat\Models\Mukhtasibat;
use Netibackend\Laravel\Serializers\AbstractProperties;

class MukhtasibatSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Mukhtasibat::class => [
                'id',
                'title'
            ]
        ];
    }
}
