<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


class TopicType extends AbstractTopicType
{

    public function translateString(): string
    {
        return 'app.lesson.topic.types.type';
    }

    public function translateType(): string
    {
        return 'empty';
    }
}
