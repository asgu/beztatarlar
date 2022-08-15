<?php


namespace Modules\UserProgress\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Certificate\Services\CertificateGeneratorService;
use Modules\Certificate\Services\CertificateService;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Exceptions\TopicIsNotAccessible;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;
use Modules\Lesson\Services\LessonService;
use Modules\LessonTest\Models\LessonTest;
use Modules\LessonTest\Services\LessonTestService;
use Modules\User\Models\User;
use Modules\User\Modules\Profile\Services\UserProfileService;
use Modules\UserProgress\Dto\LinkDto;
use Modules\UserProgress\Exceptions\TestIsNotAccessible;
use Modules\UserProgress\Modules\Lesson\Services\LessonUserProgressService;
use Modules\UserProgress\Modules\Topic\Models\TopicUserProgress;
use Modules\UserProgress\Modules\Topic\Services\TopicUserProgressService;
use Modules\UserTestResult\Models\UserTestResult;
use Modules\UserTestResult\Services\UserTestResultService;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\DTO\AbstractDto;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;

class UserProgressService
{
    /**
     * @var LessonUserProgressService
     */
    private LessonUserProgressService $lessonProgressService;
    /**
     * @var LessonService
     */
    private LessonService $lessonService;
    /**
     * @var LessonTopicService
     */
    private LessonTopicService $topicService;
    /**
     * @var TopicUserProgressService
     */
    private TopicUserProgressService $topicProgressService;
    /**
     * @var LessonTestService
     */
    private LessonTestService $testService;
    /**
     * @var UserTestResultService
     */
    private UserTestResultService $testResultService;
    /**
     * @var CertificateService
     */
    private CertificateService $certificateService;
    /**
     * @var CertificateGeneratorService
     */
    private CertificateGeneratorService $certificateGeneratorService;
    /**
     * @var UserProfileService
     */
    private UserProfileService $profileService;

    public function __construct(
        LessonService $lessonService,
        LessonUserProgressService $lessonProgressService,
        LessonTopicService $topicService,
        TopicUserProgressService $topicProgressService,
        LessonTestService $testService,
        UserTestResultService $testResultService,
        CertificateService $certificateService,
        CertificateGeneratorService $certificateGeneratorService,
        UserProfileService $profileService
    ){
        $this->lessonProgressService = $lessonProgressService;
        $this->lessonService = $lessonService;
        $this->topicService = $topicService;
        $this->topicProgressService = $topicProgressService;
        $this->testService = $testService;
        $this->testResultService = $testResultService;
        $this->certificateService = $certificateService;
        $this->certificateGeneratorService = $certificateGeneratorService;
        $this->profileService = $profileService;
    }

    /**
     * @param User $user
     * @return int
     */
    public function calculateProgress(User $user): int
    {
        $totalTopics = $this->topicService->getActiveTopicsCount();
        $totalTests = $this->testService->getActiveTestsCount();
        $passedTopics = $this->topicProgressService->getActivePassedTopicsCount($user);
        $passedTests = $this->testResultService->getActivePassedTestsCount($user);
        $total = $totalTests + $totalTopics;
        $passed = $passedTests + $passedTopics;

        if ($total == 0) {
            return 0;
        }

        return round($passed / $total * 100);
    }

    public function prepareLessons(User $user, array $lessons)
    {
        $openLessons = [];
        foreach ($lessons as $lesson) {
            /** @var Lesson $lesson */
            if ($lesson->isBlocked()) {
                break;
            }
            $openLessons[] = $lesson;
        }
        $passedTopics = $this->topicProgressService->getTopicsByUserAndLessons($user, $openLessons);
        $passedTests = $this->testResultService->getPassedByUserAndLessons($user, $openLessons);

        $this->setLessonProgress($openLessons, $passedTopics, $passedTests);
        $this->setTopicsPassed($openLessons, $passedTopics);
        $this->unblockTests($openLessons);
        $this->setTestsPassed($openLessons, $passedTests);
    }

    /**
     * @param Lesson[] $openLessons
     * @param Collection $passedTopics
     * @param Collection $passedTests
     */
    private function setLessonProgress(array $openLessons, Collection $passedTopics, Collection $passedTests)
    {
        foreach ($openLessons as $lesson) {
            $passedTopicsCount = $passedTopics->filter(function (TopicUserProgress $topicProgress) use ($lesson) {
                return $topicProgress->lesson_id == $lesson->id;
            })->count();
            $passedTestsCount = 0;
            if ($lesson->activeTest()->exists()) {
                $passedTestsCount = $passedTests->filter(function (UserTestResult $testResult) use ($lesson) {
                    return $testResult->lessonTest->lesson_id == $lesson->id;
                })->unique('lesson_test_id')->count();
            }

            $progress = round($this->calculateLessonProgress($lesson, $passedTopicsCount, $passedTestsCount));
            $lesson->setProgress($progress);
        }
    }

    /**
     * @param Lesson $lesson
     * @param int $passedTopicsCount
     * @param int $passedTestsCount
     * @return float
     */
    private function calculateLessonProgress(Lesson $lesson, int $passedTopicsCount, int $passedTestsCount): float
    {
        $total = $lesson->activeTopics()->count() + $lesson->activeTest()->count();
        if ($total == 0) {
            return 0;
        }
        $passed = $passedTopicsCount + $passedTestsCount;
        return $passed / $total * 100;
    }

    /**
     * @param Lesson[] $openLessons
     */
    private function unblockTests(array $openLessons)
    {
        foreach ($openLessons as $lesson) {
            if (!$lesson->activeTest()->exists()) {
                continue;
            }
            $blocked = false;
            if ($lesson->activeTopics()->exists()) {
                foreach ($lesson->activeTopics->all() as $topic) {
                    if (!$topic->isPassed()) {
                        $blocked = true;
                        break;
                    }
                }
            }
            $lesson->activeTest->setBlocked($blocked);
        }
    }

    /**
     * @param Lesson[] $openLessons
     * @param Collection $passedTopics
     */
    private function setTopicsPassed(array $openLessons, Collection $passedTopics)
    {
        foreach ($openLessons as $lesson) {
            foreach ($lesson->activeTopics as $topic) {
                /** @var LessonTopic $topic */
                $isPassed = (bool) $passedTopics->filter(function (TopicUserProgress $topicProgress) use ($topic) {
                    return $topicProgress->topic_id == $topic->id;
                })->count();
                $topic->setPassed($isPassed);
            }
        }
    }

    /**
     * @param Lesson[] $openLessons
     * @param Collection $passedTests
     */
    private function setTestsPassed(array $openLessons, Collection $passedTests)
    {
        foreach ($openLessons as $lesson) {
            if (!$lesson->activeTest()->exists()) {
                continue;
            }
            $lessonTest = $lesson->activeTest;
            $isPassed = (bool) $passedTests->filter(function (UserTestResult $testResult) use ($lessonTest) {
                return $testResult->lesson_test_id == $lessonTest->id;
            })->count();
            $lessonTest->setPassed($isPassed);
        }
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     */
    public function ensureTopicOpen(User $user, LessonTopic $topic): void
    {
        $prevLessons = $this->lessonService->getAllPreviousLessons($topic->lesson);
        if ($prevLessons->count() == 0) {
            return;
        }
        $prevLesson = $prevLessons->all()[0];
        foreach ($prevLessons->all() as $lesson) {
            if ($lesson->priority > $prevLesson->priority) {
                $prevLesson = $lesson;
            }
        }
        $progress = $this->lessonProgressService->isLessonPassed($user, $prevLesson);
        if (!$progress) {
            throw new TopicIsNotAccessible(__('app.lesson.errors.topicNotAccessible'));
        }
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    public function isLessonDone(User $user, Lesson $lesson): bool
    {
        if ($lesson->activeTest()->exists()) {
            return $this->isTestPassed($user, $lesson);
        }
        return $this->isTopicsPassed($user, $lesson);
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    private function isTestPassed(User $user, Lesson $lesson): bool
    {
        $doneTest = $this->testResultService->getPassedByUserAndLesson($user, $lesson);
        return $doneTest ? true : false;
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    public function isTopicsPassed(User $user, Lesson $lesson): bool
    {
        $activeTopics = $this->topicService->getAllActiveByLesson($lesson);
        $passed = $this->topicProgressService->getAllByUserAndTopics($user, $activeTopics);
        return $activeTopics->count() == $passed->count();
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return bool
     */
    public function isTopicPassed(User $user, LessonTopic $topic): bool
    {
        return $this->topicProgressService->isTopicPassedByUser($user, $topic);
    }

    /**
     * Возвращает не пройденный урок с минимальным приоритетом
     *
     * @param User $user
     * @return Builder|Model|Lesson|null
     */
    public function getCurrentLesson(User $user): ?Lesson
    {
        $maxPriority = $this->lessonProgressService->getPassedMaxPriority($user);
        return $this->lessonService->getNextActiveByPriority($maxPriority);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isWholeCourseDone(User $user): bool
    {
        return $this->getCurrentLesson($user) ? false : true;
    }

    /**
     * @param LessonTopic $topic
     * @return LessonTopic|null
     */
    public function getNextTopic(LessonTopic $topic): ?LessonTopic
    {
        if ($nextTopic = $this->topicService->getNextTopic($topic)) {
            return $nextTopic;
        }

        if ($nextLesson = $this->lessonService->getNextActiveByPriority($topic->lesson->priority)) {
            return $this->topicService->getFirstTopic($nextLesson);
        }

        return null;
    }

    /**
     * @param LessonTopic $topic
     * @return Builder|Model|LessonTopic|object|null
     */
    public function getPreviousTopic(LessonTopic $topic): ?LessonTopic
    {
        if ($prevTopic = $this->topicService->getPreviousTopic($topic)) {
            return $prevTopic;
        }

        if ($prevLesson = $this->lessonService->getPreviousLesson($topic->lesson)) {
            return $this->topicService->getLastTopic($prevLesson);
        }
        return null;
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     */
    public function ensureTestOpen(User $user, Lesson $lesson)
    {
        if (!$this->isTopicsPassed($user, $lesson)) {
            throw new TestIsNotAccessible(__('app.lesson.errors.testNotAccessible'));
        }
    }

    /**
     * @param User $user
     * @return File|null
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws PdfReaderException
     * @throws Exception
     */
    public function createCertificate(User $user): ?File
    {
        if (!$this->isWholeCourseDone($user)) {
            return null;
        }
        if ($user->profile->certificate) {
            return $user->profile->certificate;
        }
        $templatePath = $this->certificateService->getActive();
        $certificate = $this->certificateGeneratorService->generate($user, $templatePath);
        $this->profileService->attachCertificate($user->profile, $certificate);

        return $certificate;
    }

    /**
     * @param User $user
     * @param LessonTopic|null $topic
     */
    public function setTopicPassedIndicator(User $user, ?LessonTopic $topic): void
    {
        if (!$topic) {
            return;
        }
        $isPassed = $this->isTopicPassed($user, $topic);
        $topic->setPassed($isPassed);
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return LinkDto|null
     */
    public function getNextLink(User $user, LessonTopic $topic): ?LinkDto
    {
        $nextTopic = $this->getNextTopic($topic);
        $test = $topic->lesson->activeTest;
        if (!$test && !$nextTopic) {
            return null;
        }
        if ($nextTopic) {
            $this->setTopicPassedIndicator($user, $nextTopic);
        }
        if ($test) {
            $test->setPassed($this->isTestPassed($user, $topic->lesson));
        }

        return $this->generateLink($topic, $nextTopic, $test);
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return LinkDto|null
     */
    public function getPreviousLink(User $user, LessonTopic $topic): ?LinkDto
    {
        $prevTopic = $this->getPreviousTopic($topic);
        if (!$prevTopic) {
            return null;
        }
        $test = $prevTopic->lesson->activeTest;
        $this->setTopicPassedIndicator($user, $prevTopic);
        if ($test) {
            $test->setPassed($this->isTestPassed($user, $prevTopic->lesson));
        }
        return $this->generateLink($topic, $prevTopic, $test);
    }

    /**
     * @param LessonTopic $currentTopic
     * @param LessonTopic|null $anotherTopic
     * @param LessonTest|null $test
     * @return LinkDto|null
     */
    public function generateLink(LessonTopic $currentTopic, ?LessonTopic $anotherTopic = null, ?LessonTest $test = null): ?LinkDto
    {
        if ($anotherTopic && ($currentTopic->lesson_id == $anotherTopic->lesson_id || !$test)) {
            return LinkDto::populateByArray([
                'type' => LinkDto::TYPE_TOPIC,
                'lessonId' => $anotherTopic->lesson_id,
                'id' => $anotherTopic->id,
                'title' => $anotherTopic->title,
                'isPassed' => $anotherTopic->isPassed()
            ]);
        }

        if ($test) {
            return LinkDto::populateByArray([
                'type' => LinkDto::TYPE_TEST,
                'lessonId' => $test->lesson_id,
                'id' => $test->id,
                'title' => $test->test->title,
                'isPassed' => $test->isPassed()
            ]);
        }

        return null;
    }
}
