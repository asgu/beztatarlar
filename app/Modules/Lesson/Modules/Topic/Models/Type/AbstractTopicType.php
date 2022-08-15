<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


abstract class AbstractTopicType
{
    abstract public function translateString(): string;

    abstract public function translateType(): string;

    public function visit(TopicTypeVisitor $visitor): void
    {
        $visitor->handle($this);
    }
}
