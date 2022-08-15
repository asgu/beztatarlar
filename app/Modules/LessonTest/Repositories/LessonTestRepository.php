<?php


namespace Modules\LessonTest\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\LessonTest\Models\LessonTest;
use Modules\Test\Models\Test;

class LessonTestRepository
{
    /**
     * @param LessonTest $lessonTest
     */
    public function save(LessonTest $lessonTest): void
    {
        $lessonTest->save();
        $lessonTest->refresh();
    }

    /**
     * @param $id
     * @return LessonTest|null
     */
    public function findById($id): ?LessonTest
    {
        return LessonTest::find($id);
    }

    /**
     * @param Test $test
     * @param LessonTest $exceptTest
     */
    public function setInactiveByTest(Test $test, LessonTest $exceptTest): void
    {
        LessonTest::query()
            ->where('id', '<>', $exceptTest->id)
            ->where('test_id', $test->id)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->update(['status' => ActivityStatusFacade::STATUS_INACTIVE]);
    }

    /**
     * @return int
     */
    public function findActiveTestsCount(): int
    {
        return LessonTest::query()
            ->leftJoin('lessons', 'lessons.id', '=', 'lesson_tests.lesson_id')
            ->where('lesson_tests.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->where('lessons.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->count();
    }

    /**
     * @param $id
     * @return Builder|Model|object|null
     */
    public function findActiveById($id): ?LessonTest
    {
        return LessonTest::query()
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->where('id', $id)
            ->first();
    }
}
