<?php


namespace Modules\UserProgress\Modules\Topic\Factories;


use App\Helpers\DateHelper;
use Exception;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\User\Models\User;
use Modules\UserProgress\Modules\Topic\Models\TopicUserProgress;

class TopicUserProgressFactory
{
    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return TopicUserProgress
     * @throws Exception
     */
    public function create(User $user, LessonTopic $topic): TopicUserProgress
    {
        $progress = new TopicUserProgress();
        $progress->user_id = $user->id;
        $progress->topic_id = $topic->id;
        $progress->lesson_id = $topic->lesson_id;
        $progress->created_at = DateHelper::now();

        return $progress;
    }
}
