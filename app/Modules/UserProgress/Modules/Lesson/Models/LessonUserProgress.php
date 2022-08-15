<?php

namespace Modules\UserProgress\Modules\Lesson\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class LessonUserProgress
 * @package Modules\UserProgress\Modules\Lesson\Models
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property string $created_at
 * @property User $user
 * @property Lesson $lesson
 */
class LessonUserProgress extends BaseEloquent
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
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
}
