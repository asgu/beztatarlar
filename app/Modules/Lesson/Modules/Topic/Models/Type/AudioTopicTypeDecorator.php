<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


class AudioTopicTypeDecorator extends AbstractTopicTypeDecorator
{

    /**
     * @inheritDoc
     */
    public function translateType(): string
    {
        return 'audio';
    }
}
