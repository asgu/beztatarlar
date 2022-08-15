<?php


namespace Modules\Teacher\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Teacher\Models\Teacher;

class TeacherFactory
{
    /**
     * @return Teacher
     */
    public function create(): Teacher
    {
        $teacher = new Teacher();
        $teacher->status = ActivityStatusFacade::STATUS_ACTIVE;

        return $teacher;
    }
}
