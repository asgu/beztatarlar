<?php


namespace Modules\Lesson\Modules\Topic\Dto;


use Illuminate\Http\UploadedFile;
use Netibackend\Laravel\DTO\AbstractDto;

class LessonTopicDto extends AbstractDto
{
    /** @var mixed|int */
    public $lesson_id;

    /** @var mixed|string */
    public $title;

    /** @var mixed|string */
    public $video_title;

    /** @var mixed|string */
    public $video_url;

    /** @var mixed|string */
    public $content_title;

    /** @var mixed|string */
    public $content_text;

    /** @var mixed|string */
    public $audio_title;

    /** @var mixed|string */
    public $audio_description;

    /** @var mixed|UploadedFile */
    public $audio = null;

    /** @var mixed|string */
    public $priority;

    /** @var mixed|int */
    public $timer;

    /** @var mixed|string */
    public $status;

}
