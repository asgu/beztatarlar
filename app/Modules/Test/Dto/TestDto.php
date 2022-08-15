<?php


namespace Modules\Test\Dto;


use Modules\LessonTest\Dto\LessonDto;
use Netibackend\Laravel\DTO\AbstractDto;

/**
 * Class TestDto
 * @package Modules\Test\Dto
 * @property ?LessonDto $lesson
 */
class TestDto extends AbstractDto
{
    /** @var mixed|string */
    public $title;

    /** @var mixed|string */
    public $timer;

    /** @var mixed|string */
    public $description;

    /** @var mixed|string */
    public $status;

    protected ?LessonDto $_lesson = null;

    /**
     * @return LessonDto|null
     */
    public function getLesson(): ?LessonDto
    {
        return $this->_lesson;
    }

    /**
     * @param $value
     */
    public function setLesson($value): void
    {
        $this->_lesson = LessonDto::populateByArray($value);
    }
}
