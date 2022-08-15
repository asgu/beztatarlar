<?php


namespace Modules\LessonTest\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\LessonTest\Models\LessonTest;

class LessonTestFactory
{
    /**
     * @return LessonTest
     */
    public function create(): LessonTest
    {
        $lessonTest = new LessonTest();
        $lessonTest->status = ActivityStatusFacade::STATUS_ACTIVE;

        return $lessonTest;
    }
}
