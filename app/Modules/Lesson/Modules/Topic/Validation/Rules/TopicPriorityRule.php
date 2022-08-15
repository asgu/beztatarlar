<?php


namespace Modules\Lesson\Modules\Topic\Validation\Rules;


use Illuminate\Contracts\Validation\Rule;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;

class TopicPriorityRule implements Rule
{
    private $ignoreId;
    private $lessonId;
    private $status;

    public function __construct($ignoreId, $lessonId, $status)
    {
        $this->ignoreId = $ignoreId;
        $this->lessonId = $lessonId;
        $this->status = $status;
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        if ($this->status != ActivityStatusFacade::STATUS_ACTIVE) {
            return true;
        }
        /** @var LessonTopicService $service */
        $service = app(LessonTopicService::class);
        $count = $service->getCountByLessonAndPriority($this->lessonId, $value, $this->ignoreId);
        return $count == 0;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return __('app.lesson.errors.topicWithExistingPriority');
    }
}
