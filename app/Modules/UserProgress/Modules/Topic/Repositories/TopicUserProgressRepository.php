<?php


namespace Modules\UserProgress\Modules\Topic\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\User\Models\User;
use Modules\UserProgress\Modules\Topic\Models\TopicUserProgress;

class TopicUserProgressRepository
{

    /**
     * @param User $user
     * @return int
     */
    public function findActivePassedByUser(User $user): int
    {
        return TopicUserProgress::query()
            ->leftJoin('lesson_topics', 'topic_user_progress.topic_id', '=', 'lesson_topics.id')
            ->where('lesson_topics.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->where('topic_user_progress.user_id', $user->id)
            ->count();
    }

    /**
     * @param User $user
     * @param Lesson[] $lessons
     * @return Collection
     */
    public function findActivePassedByUserAndLessons(User $user, array $lessons): Collection
    {
        $ids = array_map(function (Lesson $lesson) { return $lesson->id; }, $lessons);
        return TopicUserProgress::query()
            ->where('topic_user_progress.user_id', $user->id)
            ->whereIn('topic_user_progress.lesson_id', $ids)
            ->whereHas('topic', function (Builder $query) {
                $query->where('lesson_topics.status', ActivityStatusFacade::STATUS_ACTIVE);
            })
            ->get();
    }

    /**
     * @param TopicUserProgress $progress
     */
    public function save(TopicUserProgress $progress): void
    {
        $progress->save();
        $progress->refresh();
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return Builder|Model|TopicUserProgress|null
     */
    public function findByUserAndTopic(User $user, LessonTopic $topic): ?TopicUserProgress
    {
        return TopicUserProgress::query()
            ->where('user_id', $user->id)
            ->where('topic_id', $topic->id)
            ->first();
    }

    /**
     * @param User $user
     * @param Collection $activeTopics
     * @return Collection
     */
    public function findAllByUserAndTopics(User $user, Collection $activeTopics): Collection
    {
        $ids = array_map(function (LessonTopic $topic) { return $topic->id; }, $activeTopics->all());
        return TopicUserProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('topic_id', $ids)
            ->get();
    }
}
