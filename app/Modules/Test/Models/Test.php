<?php

namespace Modules\Test\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\LessonTest\Models\LessonTest;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;
use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Test
 * @package Modules\Test\Models
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property string $timer
 * @property LessonTest|null $activeLessonTest
 * @property Lesson|null $lesson
 * @property Collection $questionLinks
 */
class Test extends BaseEloquent
{
    use HasTranslatableIntlTrait;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'status',
        'timer'
    ];

    public function getTranslatableFields(): array
    {
        return ['title', 'description'];
    }

    public function getIntlModelClass(): string
    {
        return TestIntl::class;
    }

    /**
     * @return HasOne
     */
    public function activeLessonTest(): HasOne
    {
        return $this->hasOne(LessonTest::class, 'test_id', 'id')
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE);
    }

    /**
     * @return HasOneThrough
     */
    public function lesson(): HasOneThrough
    {
        return $this->hasOneThrough(
            Lesson::class,
            LessonTest::class,
            'test_id',
            'id',
            'id',
            'lesson_id'
        )->where('lesson_tests.status', ActivityStatusFacade::STATUS_ACTIVE);
    }

    /**
     * @return HasMany
     */
    public function questionLinks(): HasMany
    {
        return $this->hasMany(TestQuestionLink::class, 'test_id', 'id')
            ->orderBy('test_question_links.priority');
    }
}
