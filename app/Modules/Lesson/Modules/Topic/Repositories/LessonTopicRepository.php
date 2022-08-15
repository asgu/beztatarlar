<?php


namespace Modules\Lesson\Modules\Topic\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;

class LessonTopicRepository
{
    /**
     * @param LessonTopic $topic
     */
    public function save(LessonTopic $topic): void
    {
        $topic->save();
        $topic->refresh();
    }

    /**
     * @param Lesson $lesson
     * @return Builder
     */
    public function getQuery(Lesson $lesson): Builder
    {
        return LessonTopic::query()
            ->where('lesson_id', $lesson->id);
    }

    /**
     * @param $id
     * @return Model|LessonTopic|null
     */
    public function findById($id): ?LessonTopic
    {
        return LessonTopic::query()->with(['audio'])->where('id', $id)->first();
    }

    /**
     * @return int
     */
    public function findActiveCount(): int
    {
        return LessonTopic::query()
            ->leftJoin('lessons', 'lessons.id', '=', 'lesson_topics.lesson_id')
            ->where('lesson_topics.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->where('lessons.status', ActivityStatusFacade::STATUS_ACTIVE)
            ->count();
    }

    public function findActiveById($id)
    {
        return LessonTopic::query()
            ->with(['audio'])
            ->where('id', $id)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->first();
    }

    /**
     * @param Lesson $lesson
     * @return Collection
     */
    public function findAllActiveByLesson(Lesson $lesson): Collection
    {
        return LessonTopic::query()
            ->where('lesson_id', $lesson->id)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->get();
    }

    /**
     * @param $lessonId
     * @param $priority
     * @param $ignoreId
     * @return int
     */
    public function findActiveCountByLessonAndPriority($lessonId, $priority, $ignoreId): int
    {
        return LessonTopic::query()
            ->where('lesson_id', $lessonId)
            ->where('priority', $priority)
            ->where('id', '<>', $ignoreId)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->count();
    }

    /**
     * @param LessonTopic $topic
     * @return Builder|Model|LessonTopic|null
     */
    public function findNext(LessonTopic $topic): ?LessonTopic
    {
        return LessonTopic::query()
            ->where('lesson_id', $topic->lesson_id)
            ->where('priority', '>', $topic->priority)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->orderBy('priority')
            ->first();
    }

    /**
     * @param Lesson|null $lesson
     * @return Builder|Model|LessonTopic|null
     */
    public function findFirstActiveByLesson(?Lesson $lesson): ?LessonTopic
    {
        return LessonTopic::query()
            ->where('lesson_id', $lesson->id)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->orderBy('priority')
            ->first();
    }

    /**
     * @param LessonTopic $topic
     * @return Builder|Model|object|null
     */
    public function findPrevious(LessonTopic $topic): ?LessonTopic
    {
        return LessonTopic::query()
            ->where('lesson_id', $topic->lesson_id)
            ->where('priority', '<', $topic->priority)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->orderBy('priority', 'desc')
            ->first();
    }

    /**
     * @param Lesson|null $lesson
     * @return Builder|Model|object|null
     */
    public function findLastActiveByLesson(?Lesson $lesson): ?LessonTopic
    {
        return LessonTopic::query()
            ->where('lesson_id', $lesson->id)
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE)
            ->orderBy('priority', 'desc')
            ->first();
    }


}
