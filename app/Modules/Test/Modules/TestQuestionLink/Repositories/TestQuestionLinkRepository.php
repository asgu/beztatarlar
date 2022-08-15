<?php


namespace Modules\Test\Modules\TestQuestionLink\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;

class TestQuestionLinkRepository
{
    /**
     * @param TestQuestionLink $link
     */
    public function save(TestQuestionLink $link): void
    {
        $link->save();
        $link->refresh();
    }

    /**
     * @param Test $test
     * @return Builder
     */
    public function getQuery(Test $test): Builder
    {
        return TestQuestionLink::query()
            ->where('test_id', $test->id)
            ->with(['question']);
    }

    /**
     * @param Test $test
     * @param TestQuestion $question
     * @return Builder|Model|TestQuestionLink|null
     */
    public function findByTestAndQuestion(Test $test, TestQuestion $question): ?TestQuestionLink
    {
        return TestQuestionLink::query()
            ->where('test_id', $test->id)
            ->where('question_id', $question->id)
            ->first();
    }

    /**
     * @param TestQuestionLink $link
     */
    public function delete(TestQuestionLink $link)
    {
        $link->delete();
    }

    /**
     * @param $testId
     * @param $priority
     * @param $ignoreId
     * @return int
     */
    public function findCountByTestAndPriority($testId, $priority, $ignoreId): int
    {
        return TestQuestionLink::query()
            ->where('test_id', $testId)
            ->where('priority', $priority)
            ->where('id', '<>', $ignoreId)
            ->count();
    }
}
