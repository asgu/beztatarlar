<?php


namespace Modules\Test\Serializers;


use Modules\Test\Models\Test;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;
use Modules\Test\Modules\TestQuestionLink\Serializers\FullTestQuestionLinkSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class FullTestSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Test::class => [
                'title',
                'timer',
                'description',
                'questionLinks',
            ],
            TestQuestionLink::class => FullTestQuestionLinkSerializer::class
        ];
    }
}
