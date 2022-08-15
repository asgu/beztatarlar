<?php


namespace Modules\Position\Serializers;


use Modules\Position\Models\Position;
use Netibackend\Laravel\Serializers\AbstractProperties;

class PositionSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Position::class => [
                'id',
                'title'
            ]
        ];
    }
}
