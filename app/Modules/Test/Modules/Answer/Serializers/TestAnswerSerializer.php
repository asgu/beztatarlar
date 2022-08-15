<?php


namespace Modules\Test\Modules\Answer\Serializers;


use Modules\Test\Modules\Answer\Models\TestAnswer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class TestAnswerSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            TestAnswer::class => [
                'id',
                'answer'
            ]
        ];
    }
}
