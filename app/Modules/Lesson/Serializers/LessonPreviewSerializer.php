<?php


namespace Modules\Lesson\Serializers;


use Modules\Lesson\Models\Lesson;
use Netibackend\Laravel\Serializers\AbstractProperties;

class LessonPreviewSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Lesson::class => [
                'id',
                'title'
            ]
        ];
    }
}
