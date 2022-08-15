<?php

namespace Modules\Lesson\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\LessonTest\Models\LessonTest;
use Modules\Test\Models\Test;
use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Lesson
 * @package Modules\Lesson\Models
 * @property int $id
 * @property string $title
 * @property int $priority
 * @property string $status
 * @property LessonTest|null $activeTest
 * @property Test|null $test
 * @property Collection $activeTopics
 */
class Lesson extends BaseEloquent
{
    use HasTranslatableIntlTrait;

    public $timestamps = false;

    private bool $blocked = true;
    private int $progress = 0;

    protected $fillable = [
        'title',
        'priority',
        'status'
    ];

    public function getTranslatableFields(): array
    {
        return ['title'];
    }

    public function getIntlModelClass(): string
    {
        return LessonIntl::class;
    }

    public function setBlocked(bool $isBlocked = true): void
    {
        $this->blocked = $isBlocked;
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @return HasMany
     */
    public function activeTopics(): HasMany
    {
        return $this->hasMany(LessonTopic::class, 'lesson_id', 'id')
            ->where('lesson_topics.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->orderBy('lesson_topics.priority');
    }

    /**
     * @return HasOne
     */
    public function activeTest(): HasOne
    {
        return $this->hasOne(LessonTest::class, 'lesson_id', 'id')
            ->where('lesson_tests.status', ActivityStatusFacade::STATUS_ACTIVE);
    }

    /**
     * @return HasOneThrough
     */
    public function test(): HasOneThrough
    {
        return $this->hasOneThrough(
            Test::class,
            LessonTest::class,
            'lesson_id',
            'id',
            'id',
            'test_id'
        );
    }

    /**
     * @return int
     */
    public function getProgress(): int
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     */
    public function setProgress(int $progress): void
    {
        $this->progress = $progress;
    }
}
