<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Api\Models\Breadcrumbs\Breadcrumb;
use Modules\Api\Models\Breadcrumbs\Breadcrumbs;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;
use Modules\Lesson\Serializers\LessonPreviewSerializer;
use Modules\Lesson\Services\LessonService;
use Modules\LessonTest\Services\LessonTestService;
use Modules\Test\Modules\Question\Dto\QuestionListDto;
use Modules\Test\Serializers\FullTestSerializer;
use Modules\Test\Serializers\TestResultSerializer;
use Modules\Test\Services\TestService;
use Modules\UserProgress\Modules\Lesson\Services\LessonUserProgressService;
use Modules\UserProgress\Serializers\LinkSerializer;
use Modules\UserProgress\Services\UserProgressService;
use Modules\UserTestResult\Models\UserTestResult;
use Modules\UserTestResult\Services\UserTestResultService;
use Neti\Laravel\Files\Serializers\FileSerializer;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class LessonTestController extends BaseApiController
{
    /**
     * @var UserProgressService
     */
    private UserProgressService $progressService;
    /**
     * @var LessonTestService
     */
    private LessonTestService $lessonTestService;
    /**
     * @var TestService
     */
    private TestService $testService;
    /**
     * @var UserTestResultService
     */
    private UserTestResultService $testResultService;
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

    public function __construct(
        UserProgressService $progressService,
        LessonTestService $lessonTestService,
        TestService $testService,
        UserTestResultService $testResultService,
        LessonUserProgressService $lessonProgressService,
        LessonService $lessonService,
        LessonTopicService $topicService
    ) {
        $this->progressService = $progressService;
        $this->lessonTestService = $lessonTestService;
        $this->testService = $testService;
        $this->testResultService = $testResultService;
        $this->lessonProgressService = $lessonProgressService;
        $this->lessonService = $lessonService;
        $this->topicService = $topicService;
    }

    /**
     * @param $id
     * @return array
     * @throws AuthenticationException
     */
    #[ArrayShape(['isTestOpen' => "bool"])]
    public function isTestOpen($id): array
    {
        $lessonTest = $this->lessonTestService->tryGetActiveById($id);
        $topicsPassed = $this->progressService->isTopicsPassed($this->getCurrentUser(), $lessonTest->lesson);

        return [
            'isTestOpen' => $topicsPassed
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function test($id): array
    {
        $lessonTest = $this->lessonTestService->tryGetActiveById($id);
        $test = $this->testService->tryGetFullTestById($lessonTest->test_id);
        $nextLesson = $this->lessonService->getNextActiveByPriority($test->lesson->priority);

        $previousTopic = $this->topicService->getLastTopic($test->lesson);
        $nextTopic = $this->topicService->getFirstTopic($nextLesson);
        $this->progressService->setTopicPassedIndicator($this->getCurrentUser(), $previousTopic);
        $this->progressService->setTopicPassedIndicator($this->getCurrentUser(), $nextTopic);

        $nextLink = $nextTopic ? $this->progressService->generateLink($nextTopic, $nextTopic) : null;
        $previousLink = $previousTopic ? $this->progressService->generateLink($previousTopic, $previousTopic) : null;

        $lesson = $test->activeLessonTest->lesson;
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs
            ->add(
                (new Breadcrumb($lesson->id, $lesson->title))->setLinkAttr('lessonId')
            )
            ->add(
                (new Breadcrumb($id, $test->title))->setLinkAttr('testId')
            );

        return [
            'id' => $id,
            'test' => FullTestSerializer::serialize($test),
            'next' => LinkSerializer::serialize($nextLink),
            'previous' => LinkSerializer::serialize($previousLink),
            'breadcrumbs' => $breadcrumbs->toArray()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     * @throws AuthenticationException
     * @throws DataValidationException
     * @throws Exception
     */
    public function check(Request $request, $id): array
    {
        $lessonTest = $this->lessonTestService->tryGetActiveById($id);
        $this->progressService->ensureTestOpen($this->getCurrentUser(), $lessonTest->lesson);

        $test = $this->testService->tryGetById($lessonTest->test_id, ['questionLinks']);
        $questions = QuestionListDto::populateByArray($request->all());
        $result = $this->testService->checkTest($test, $questions);

        $this->testResultService->saveResults($this->getCurrentUser(), $lessonTest, $result);
        $lessonDone = false;

        if ($result->isCorrect) {
            $this->lessonProgressService->setPassed($this->getCurrentUser(), $test->lesson);
            $lessonDone = true;
        }
        $nextLesson = $this->progressService->getCurrentLesson($this->getCurrentUser());
        $certificate = $this->progressService->createCertificate($this->getCurrentUser());

        return [
            'id' => $id,
            'result' => TestResultSerializer::serialize($result),
            'currentLessonDone' => $lessonDone,
            'nextLesson' => $nextLesson ? LessonPreviewSerializer::serialize($nextLesson) : $nextLesson,
            'certificate' => $certificate ? FileSerializer::serialize($certificate) : null
        ];
    }

}
