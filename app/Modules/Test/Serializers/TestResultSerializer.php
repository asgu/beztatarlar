<?php


namespace Modules\Test\Serializers;


use Modules\Test\Dto\TestResultDto;
use Netibackend\Laravel\Serializers\AbstractProperties;

class TestResultSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            TestResultDto::class => [
                'isCorrect',
                'questions' => 'answers'
            ]
        ];
    }
}
