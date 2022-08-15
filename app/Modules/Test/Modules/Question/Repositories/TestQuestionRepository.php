<?php


namespace Modules\Test\Modules\Question\Repositories;


use Modules\Test\Modules\Question\Models\TestQuestion;

class TestQuestionRepository
{
    /**
     * @param TestQuestion $question
     */
    public function save(TestQuestion $question): void
    {
        $question->save();
        $question->refresh();
    }

    /**
     * @param $id
     * @return TestQuestion|null
     */
    public function findById($id): ?TestQuestion
    {
        return TestQuestion::find($id);
    }

    /**
     * @param TestQuestion $question
     */
    public function delete(TestQuestion $question)
    {
        $question->delete();
    }
}
