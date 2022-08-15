<?php


namespace Modules\Lesson\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class LessonDto extends AbstractDto
{
    /** @var mixed|string */
    public $title;

    /** @var mixed|int */
    public $priority;

    /** @var mixed|string */
    public $status;
}
