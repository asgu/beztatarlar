<?php


namespace Modules\Lesson\Modules\Topic\Models\Type;


class TopicTypeVisitor
{
    private array $priorities = [];
    private ?AbstractTopicType $type = null;

    public function setClassPriority(string $decoratorClass, int $priority): self
    {
        $this->priorities[$decoratorClass] = $priority;
        return $this;
    }

    /**
     * @param AbstractTopicType $type
     */
    public function handle(AbstractTopicType $type): void
    {
        if (!$this->type) {
            $this->type = $type;
            return;
        }

        if ($this->isMorePriority($type)) {
            $this->type = $type;
        }
    }

    /**
     * @return AbstractTopicType|null
     */
    public function getType(): ?AbstractTopicType
    {
        return $this->type;
    }

    /**
     * Проверка, чей порядковый номер меньше. Чем меньше порядковый номер ($priority), тем бОльший вес этот тип имеет
     *
     * @param AbstractTopicType $type
     * @return bool
     */
    private function isMorePriority(AbstractTopicType $type): bool
    {
        $currentClass = get_class($this->type);
        $newClass = get_class($type);
        if (!isset($this->priorities[$newClass])) {
            return false;
        }
        if (!isset($this->priorities[$currentClass])) {
            return true;
        }
        return $this->priorities[$currentClass] > $this->priorities[$newClass];
    }
}
