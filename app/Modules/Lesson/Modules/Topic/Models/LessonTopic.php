<?php

namespace Modules\Lesson\Modules\Topic\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\File\Database\Eloquent\PropertyTraits\AudioTrait;
use Modules\Lesson\Models\Lesson;
use Modules\Translation\Models\HasTranslatableIntlTrait;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class LessonTopic
 * @package Modules\Lesson\Modules\Topic\Models
 * @property int $id
 * @property int $lesson_id
 * @property string $title
 * @property string $video_title
 * @property string $video_url
 * @property string $content_title
 * @property string $content_text
 * @property string $audio_title
 * @property string $audio_description
 * @property string $audio_uuid
 * @property int $priority
 * @property string $timer
 * @property string $status
 * @property File|null $audio
 * @property Lesson $lesson
 */
class LessonTopic extends BaseEloquent
{
    public const TYPE_VIDEO = 'video';
    public const TYPE_TEXT  = 'text';
    public const TYPE_AUDIO = 'audio';

    use HasTranslatableIntlTrait;
    use AudioTrait;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'video_title',
        'video_url',
        'content_title',
        'content_text',
        'audio_title',
        'audio_description',
        'priority',
        'timer',
        'status'
    ];

    private bool $passed = false;

    public function getTranslatableFields(): array
    {
        return [
            'title',
            'video_title',
            'content_title',
            'content_text',
            'audio_title',
            'audio_description',
        ];
    }

    public function getIntlModelClass(): string
    {
        return LessonTopicIntl::class;
    }

    /**
     * @return HasOne
     */
    public function audio(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'audio_uuid');
    }

    /**
     * @return HasOne
     */
    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class, 'id', 'lesson_id');
    }

    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->passed;
    }

    /**
     * @param bool $passed
     */
    public function setPassed(bool $passed): void
    {
        $this->passed = $passed;
    }
}
