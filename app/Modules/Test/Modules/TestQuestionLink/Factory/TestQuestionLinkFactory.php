<?php


namespace Modules\Test\Modules\TestQuestionLink\Factory;


use Modules\Test\Models\Test;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;

class TestQuestionLinkFactory
{
    /**
     * @param Test|null $test
     * @return TestQuestionLink
     */
    public function create(?Test $test): TestQuestionLink
    {
        $link = new TestQuestionLink();
        $link->priority = 0;

        if ($test) {
            $link->test_id = $test->id;
        }

        return $link;
    }
}
