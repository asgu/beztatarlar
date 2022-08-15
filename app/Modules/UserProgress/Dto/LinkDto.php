<?php


namespace Modules\UserProgress\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class LinkDto extends AbstractDto
{
    public const TYPE_TOPIC = 'topic';
    public const TYPE_TEST = 'test';

    /** @var mixed|int */
    public $lessonId;

    /** @var mixed|int */
    public $id;

    /** @var mixed|string */
    public $title;

    /** @var mixed|string */
    public $type;

    /** @var mixed|bool */
    public $isPassed;
}
