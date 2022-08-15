<?php


namespace Modules\Lesson\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;

class LessonFactory
{
    /**
     * @return Lesson
     */
    public function create(): Lesson
    {
        $lesson = new Lesson();
        $lesson->status = ActivityStatusFacade::STATUS_ACTIVE;
        $lesson->priority = 0;

        return $lesson;
    }
}
