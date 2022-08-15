<?php


namespace Modules\Test\Modules\TestQuestionLink\Serializers;


use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Answer\Serializers\TestAnswerSerializer;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;
use Netibackend\Laravel\Serializers\AbstractProperties;

class FullTestQuestionLinkSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            TestQuestionLink::class => [
                'linkId' => 'id',
                'questionId' => 'question_id',
                'priority',
                'question' => function (TestQuestionLink $link) {
                    return $link->question->question;
                },
                'answers' => function (TestQuestionLink $link) {
                    return $link->question->answers->shuffle();
                }
            ],
            TestAnswer::class => TestAnswerSerializer::class
        ];
    }
}
