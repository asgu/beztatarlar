<?php


namespace Modules\Test\Modules\Answer\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Question\Models\TestQuestion;

class TestAnswerRepository
{
    /**
     * @param TestAnswer $answer
     */
    public function save(TestAnswer $answer): void
    {
        $answer->save();
        $answer->refresh();
    }

    /**
     * @param $questionId
     * @return Builder
     */
    public function getQuery($questionId): Builder
    {
        return TestAnswer::query()->where('question_id', $questionId);
    }

    /**
     * @param TestQuestion $question
     */
    public function deleteByQuestion(TestQuestion $question)
    {
        TestAnswer::query()->where('question_id', $question->id)->delete();
    }

    /**
     * @param $id
     * @return TestAnswer|null
     */
    public function findById($id): ?TestAnswer
    {
        return TestAnswer::find($id);
    }

    /**
     * @param TestAnswer $answer
     */
    public function delete(TestAnswer $answer): void
    {
        $answer->delete();
    }

    /**
     * @param Test $test
     * @return Collection
     */
    public function findAllCorrect(Test $test): Collection
    {
        $questionIds = $test->questionLinks->pluck('question_id');
        return TestAnswer::query()
            ->whereIn('question_id', $questionIds)
            ->where('is_correct', TestAnswer::CORRECT)
            ->get();
    }
}
