<?php


namespace Modules\UserProgress\Modules\Lesson\Factories;


use App\Helpers\DateHelper;
use Exception;
use Modules\Lesson\Models\Lesson;
use Modules\User\Models\User;
use Modules\UserProgress\Modules\Lesson\Models\LessonUserProgress;

class LessonUserProgressFactory
{
    /**
     * @param User $user
     * @param Lesson $lesson
     * @return LessonUserProgress
     * @throws Exception
     */
    public function create(User $user, Lesson $lesson): LessonUserProgress
    {
        $progress = new LessonUserProgress();
        $progress->user_id = $user->id;
        $progress->lesson_id = $lesson->id;
        $progress->created_at = DateHelper::now();

        return $progress;
    }
}
