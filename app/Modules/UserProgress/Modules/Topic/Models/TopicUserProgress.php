<?php

namespace Modules\UserProgress\Modules\Topic\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\User\Models\User;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class TopicUserProgress
 * @package Modules\UserProgress\Modules\Topic\Models
 * @property int $id
 * @property int $topic_id
 * @property int $lesson_id
 * @property int $user_id
 * @property string $created_at
 * @property User $user
 * @property LessonTopic $topic
 * @property Lesson $lesson
 */
class TopicUserProgress extends BaseEloquent
{
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(LessonTopic::class, 'topic_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
}
