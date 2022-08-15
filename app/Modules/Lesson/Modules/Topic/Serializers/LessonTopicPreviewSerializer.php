<?php


namespace Modules\Lesson\Modules\Topic\Serializers;


use Modules\Lesson\Modules\Topic\Facades\LessonTopicFacade;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\Lesson\Modules\Topic\Models\Type\AudioTopicTypeDecorator;
use Modules\Lesson\Modules\Topic\Models\Type\TextTopicTypeDecorator;
use Modules\Lesson\Modules\Topic\Models\Type\TopicTypeVisitor;
use Modules\Lesson\Modules\Topic\Models\Type\VideoTopicTypeDecorator;
use Netibackend\Laravel\Serializers\AbstractProperties;

class LessonTopicPreviewSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            LessonTopic::class => [
                'id',
                'priority',
                'lessonId' => 'lesson_id',
                'title',
                'timer',
                'isPassed' => function (LessonTopic $topic) {
                    return $topic->isPassed();
                },
                'type' => function (LessonTopic $topic) {
                    $type = LessonTopicFacade::getTopicType($topic);
                    $visitor = (new TopicTypeVisitor())
                        ->setClassPriority(VideoTopicTypeDecorator::class, 1)
                        ->setClassPriority(AudioTopicTypeDecorator::class, 2)
                        ->setClassPriority(TextTopicTypeDecorator::class, 3);
                    $type->visit($visitor);
                    return [
                        'title' => __($type->translateString()),
                        'id' => $visitor->getType()->translateType()
                    ];
                }
            ]
        ];
    }
}
