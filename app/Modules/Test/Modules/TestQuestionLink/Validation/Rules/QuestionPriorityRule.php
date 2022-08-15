<?php


namespace Modules\Test\Modules\TestQuestionLink\Validation\Rules;


use Illuminate\Contracts\Validation\Rule;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;
use Modules\Test\Modules\TestQuestionLink\Services\TestQuestionLinkService;

class QuestionPriorityRule implements Rule
{
    private $ignoreId;
    private $testId;

    public function __construct($ignoreId, $testId)
    {
        $this->ignoreId = $ignoreId;
        $this->testId = $testId;
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        /** @var TestQuestionLinkService $service */
        $service = app(TestQuestionLinkService::class);
        $count = $service->getCountByTestAndPriority($this->testId, $value, $this->ignoreId);
        return $count == 0;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return __('app.test.question.errors.questionWithExistingPriority');
    }
}
