<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


class VideoTopicTypeDecorator extends AbstractTopicTypeDecorator
{

    /**
     * @inheritDoc
     */
    public function translateType(): string
    {
        return 'video';
    }
}
