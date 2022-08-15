<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


class TextTopicTypeDecorator extends AbstractTopicTypeDecorator
{

    /**
     * @inheritDoc
     */
    public function translateType(): string
    {
        return 'text';
    }
}
