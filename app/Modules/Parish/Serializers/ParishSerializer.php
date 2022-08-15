<?php


namespace Modules\Parish\Serializers;


use Modules\Parish\Models\Parish;
use Netibackend\Laravel\Serializers\AbstractProperties;

class ParishSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Parish::class => [
                'id',
                'title'
            ]
        ];
    }
}
