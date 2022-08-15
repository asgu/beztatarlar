<?php


namespace Modules\Test\Modules\Question\Factories;


use Modules\Test\Modules\Question\Models\TestQuestion;

class TestQuestionFactory
{
    /**
     * @return TestQuestion
     */
    public function create(): TestQuestion
    {
        return new TestQuestion();
    }
}
