<?php


namespace Modules\UserTestResult\Factories;


use App\Helpers\DateHelper;
use Exception;
use Modules\LessonTest\Models\LessonTest;
use Modules\Test\Dto\TestResultDto;
use Modules\User\Models\User;
use Modules\UserTestResult\Models\UserTestResult;

class UserTestResultFactory
{
    /**
     * @param User $user
     * @param LessonTest $lessonTest
     * @param TestResultDto $resultDto
     * @return UserTestResult
     * @throws Exception
     */
    public function create(User $user, LessonTest $lessonTest, TestResultDto $resultDto): UserTestResult
    {
        $result = new UserTestResult();
        $result->lesson_test_id = $lessonTest->id;
        $result->user_id = $user->id;
        $result->is_correct = $resultDto->isCorrect;
//        $result->answer = json_encode($resultDto->answers);
        $result->answer = $resultDto->answers;
        $result->created_at = DateHelper::now();

        return $result;
    }
}
