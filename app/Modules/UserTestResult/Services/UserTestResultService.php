<?php


namespace Modules\UserTestResult\Services;


use Exception;
use Illuminate\Database\Eloquent\Collection;
use Modules\Lesson\Models\Lesson;
use Modules\LessonTest\Models\LessonTest;
use Modules\Test\Dto\TestResultDto;
use Modules\User\Models\User;
use Modules\UserTestResult\Factories\UserTestResultFactory;
use Modules\UserTestResult\Models\UserTestResult;
use Modules\UserTestResult\Repositories\UserTestResultRepository;

class UserTestResultService
{
    /**
     * @var UserTestResultRepository
     */
    private UserTestResultRepository $repository;
    /**
     * @var UserTestResultFactory
     */
    private UserTestResultFactory $factory;

    public function __construct(UserTestResultRepository $repository, UserTestResultFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param User $user
     * @return int
     */
    public function getActivePassedTestsCount(User $user): int
    {
        return $this->repository->findActivePassedByUserCount($user);
    }

    /**
     * @param User $user
     * @param Lesson[] $lessons
     * @return Collection
     */
    public function getPassedByUserAndLessons(User $user, array $lessons): Collection
    {
        return $this->repository->findActivePassedByUserAndLessons($user, $lessons);
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return UserTestResult|null
     */
    public function getPassedByUserAndLesson(User $user, Lesson $lesson): ?UserTestResult
    {
        return $this->repository->findPassedByUserAndLesson($user, $lesson);
    }

    /**
     * @param User $user
     * @param LessonTest $lessonTest
     * @param TestResultDto $result
     * @throws Exception
     */
    public function saveResults(User $user, LessonTest $lessonTest, TestResultDto $result): void
    {
        $testResult = $this->createDraw($user, $lessonTest, $result);
        $this->save($testResult);
    }

    /**
     * @param User $user
     * @param LessonTest $lessonTest
     * @param TestResultDto $result
     * @return UserTestResult
     * @throws Exception
     */
    private function createDraw(User $user, LessonTest $lessonTest, TestResultDto $result): UserTestResult
    {
        return $this->factory->create($user, $lessonTest, $result);
    }

    /**
     * @param UserTestResult $testResult
     */
    private function save(UserTestResult $testResult)
    {
        $this->repository->save($testResult);
    }
}
