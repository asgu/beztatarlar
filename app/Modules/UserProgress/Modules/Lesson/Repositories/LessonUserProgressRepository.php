<?php


namespace Modules\UserProgress\Modules\Lesson\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;
use Modules\UserProgress\Modules\Lesson\Models\LessonUserProgress;

class LessonUserProgressRepository
{

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return Model|LessonUserProgress|null
     */
    public function findByUserAndLesson(User $user, Lesson $lesson): ?LessonUserProgress
    {
        return LessonUserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();
    }

    /**
     * @param LessonUserProgress $progress
     */
    public function save(LessonUserProgress $progress): void
    {
        $progress->save();
        $progress->refresh();
    }

    /**
     * @param User $user
     * @return int|null
     */
    public function findMaxPassedPriority(User $user): ?int
    {
        return DB::table('lessons')
            ->leftJoin('lesson_user_progress', 'lesson_user_progress.lesson_id', '=', 'lessons.id')
            ->where('lesson_user_progress.user_id', $user->id)
            ->where('lessons.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->max('lessons.priority');
    }
}
