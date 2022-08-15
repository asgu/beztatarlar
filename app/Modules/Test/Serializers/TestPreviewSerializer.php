<?php


namespace Modules\Test\Serializers;


use Modules\Test\Models\Test;
use Netibackend\Laravel\Serializers\AbstractProperties;

class TestPreviewSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Test::class => [
                'title',
                'timer'
            ]
        ];
    }
}
