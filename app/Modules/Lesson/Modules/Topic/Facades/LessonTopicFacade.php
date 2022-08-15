<?php


namespace Modules\Lesson\Modules\Topic\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\Lesson\Modules\Topic\Models\Type\AbstractTopicType;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;

/**
 * Class LessonTopicFacade
 * @package Modules\Lesson\Modules\Topic
 * @method static AbstractTopicType getTopicType(LessonTopic $topic)
 */
class LessonTopicFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LessonTopicService::class;
    }
}
