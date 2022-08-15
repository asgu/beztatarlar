<?php


namespace Modules\Test\Modules\Answer\Factories;


use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Question\Models\TestQuestion;

class TestAnswerFactory
{
    /**
     * @param bool $isCorrect
     * @return TestAnswer
     */
    public function create(bool $isCorrect = false): TestAnswer
    {
        $answer = new TestAnswer();
        $answer->is_correct = $isCorrect;

        return $answer;
    }

    /**
     * @param TestQuestion $question
     * @return TestAnswer
     */
    public function createByQuestion(TestQuestion $question): TestAnswer
    {
        $answer = $this->create(false);
        $answer->question_id = $question->id;

        return $answer;
    }
}
