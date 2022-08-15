<?php


namespace Modules\Lesson\Modules\Topic\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;

class LessonTopicFactory
{
    /**
     * @param Lesson|null $lesson
     * @return LessonTopic
     */
    public function create(?Lesson $lesson = null): LessonTopic
    {
        $topic = new LessonTopic();
        $topic->status = ActivityStatusFacade::STATUS_ACTIVE;
        $topic->priority = 1;

        if ($lesson) {
            $topic->lesson_id = $lesson->id;
            $topic->setRelation('lesson', $lesson);
        }

        return $topic;
    }
}
