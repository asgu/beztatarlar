<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


abstract class AbstractTopicTypeDecorator extends AbstractTopicType
{

    /**
     * @var AbstractTopicType
     */
    private AbstractTopicType $topicType;

    public function __construct(AbstractTopicType $topicType)
    {
        $this->topicType = $topicType;
    }

    /**
     * @return string
     */
    public function translateString(): string
    {
        $selfTranslate = $this->translateType();
        $translateString = $this->topicType->translateString();
        return $translateString . '_' . $selfTranslate;
    }

    /**
     * @param TopicTypeVisitor $visitor
     */
    public function visit(TopicTypeVisitor $visitor): void
    {
        parent::visit($visitor);
        $this->topicType->visit($visitor);
    }
}
