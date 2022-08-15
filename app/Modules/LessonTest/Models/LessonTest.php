<?php

namespace Modules\LessonTest\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Lesson\Models\Lesson;
use Modules\Test\Models\Test;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class LessonTest
 * @package Modules\LessonTest\Models
 * @property int $id
 * @property int $test_id
 * @property int $lesson_id
 * @property string $status
 * @property Test $test
 * @property Lesson $lesson
 */
class LessonTest extends BaseEloquent
{
    public $timestamps = false;

    protected $fillable = [
        'test_id',
        'lesson_id',
        'status',
    ];
    /**
     * @var bool|mixed
     */
    private bool $blocked = true;
    /**
     * @var bool|mixed
     */
    private bool $passed = false;

    /**
     * @return HasOne
     */
    public function test(): HasOne
    {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }

    /**
     * @return BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
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
