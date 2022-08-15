<?php


namespace Modules\UserProgress\Serializers;


use Modules\UserProgress\Dto\LinkDto;
use Netibackend\Laravel\Serializers\AbstractProperties;

class LinkSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            LinkDto::class => [
                'lessonId',
                'id',
                'title',
                'type',
                'isPassed',
            ]
        ];
    }
}
