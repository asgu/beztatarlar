<?php

namespace Modules\UserTestResult\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\LessonTest\Models\LessonTest;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class UserTestResult
 * @package Modules\UserTestResult\Models
 * @property  int $id
 * @property  int $lesson_test_id
 * @property  int $user_id
 * @property  int $is_correct
 * @property  string $created_at
 * @property  array $answer
 * @property LessonTest $lessonTest
 */
class UserTestResult extends BaseEloquent
{
    public const CORRECT = 1;
    public const INCORRECT = 0;

    public $timestamps = false;

    protected $casts = [
        'answer' => 'array'
    ];

    /**
     * @return BelongsTo
     */
    public function lessonTest(): BelongsTo
    {
        return $this->belongsTo(LessonTest::class, 'lesson_test_id', 'id');
    }
}
