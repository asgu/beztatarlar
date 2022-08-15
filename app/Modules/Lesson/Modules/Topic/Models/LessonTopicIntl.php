<?php

namespace Modules\Lesson\Modules\Topic\Models;

use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class LessonTopicIntl
 * @package Modules\Lesson\Modules\Topic\Models
 * @property string $title
 * @property string $video_title
 * @property string $content_title
 * @property string $content_text
 * @property string $audio_title
 * @property string $audio_description
 */
class LessonTopicIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'lesson_topics_intl';

    protected $fillable = [
        'title',
        'video_title',
        'content_title',
        'content_text',
        'audio_title',
        'audio_description',
    ];
}
