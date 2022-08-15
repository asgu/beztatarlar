<?php


namespace Modules\UserTestResult\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;
use Modules\UserTestResult\Models\UserTestResult;

class UserTestResultRepository
{

    /**
     * @param User $user
     * @return int
     */
    public function findActivePassedByUserCount(User $user): int
    {
        return UserTestResult::query()
            ->leftJoin('lesson_tests', 'user_test_results.lesson_test_id', '=', 'lesson_tests.id')
            ->where('user_test_results.user_id', $user->id)
            ->where('is_correct', UserTestResult::CORRECT)
            ->where('lesson_tests.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->distinct('user_test_results.lesson_test_id')
            ->count();
    }

    /**
     * @param User $user
     * @param array $lessons
     * @return Collection
     */
    public function findActivePassedByUserAndLessons(User $user, array $lessons): Collection
    {
        $ids = array_map(function (Lesson $lesosn) { return $lesosn->id; }, $lessons);
        return UserTestResult::query()
            ->with('lessonTest')
            ->where('user_test_results.user_id', $user->id)
            ->where('user_test_results.is_correct', UserTestResult::CORRECT)
            ->whereHas('lessonTest', function (Builder $query) use ($ids) {
                $query->whereIn('lesson_tests.lesson_id', $ids)
                    ->where('lesson_tests.status', ActivityStatusFacade::STATUS_ACTIVE);
            })
            ->get();
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return Model|UserTestResult|null
     */
    public function findPassedByUserAndLesson(User $user, Lesson $lesson): ?UserTestResult
    {
        return UserTestResult::query()
            ->where('user_id', $user->id)
            ->where('is_correct', UserTestResult::CORRECT)
            ->whereHas('lessonTest', function (Builder $query) use ($lesson) {
                $query->where('lesson_tests.lesson_id', $lesson->id);
            })
            ->first();
    }

    /**
     * @param UserTestResult $testResult
     */
    public function save(UserTestResult $testResult)
    {
        $testResult->save();
        $testResult->refresh();
    }
}
