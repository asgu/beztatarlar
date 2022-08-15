<?php


namespace Modules\Lesson\Serializers;


use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\Lesson\Modules\Topic\Serializers\LessonTopicPreviewSerializer;
use Modules\LessonTest\Models\LessonTest;
use Modules\LessonTest\Serializers\LessonTestSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class LessonListSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Lesson::class => [
                'id',
                'priority',
                'title',
                'isBlocked' => function (Lesson $lesson) {
                    return $lesson->isBlocked();
                },
                'test' => function (Lesson $lesson) {
                    return $lesson->activeTest;
                },
                'topics' => 'activeTopics',
                'progress' => function (Lesson $lesson) {
                    return $lesson->getProgress();
                }
            ],
            LessonTopic::class => LessonTopicPreviewSerializer::class,
            LessonTest::class => LessonTestSerializer::class
        ];
    }
}
