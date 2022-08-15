<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Modules\Api\Models\Breadcrumbs\Breadcrumb;
use Modules\Api\Models\Breadcrumbs\Breadcrumbs;
use Modules\Lesson\Modules\Topic\Serializers\LessonTopicPreviewSerializer;
use Modules\Lesson\Modules\Topic\Serializers\LessonTopicSerializer;
use Modules\Lesson\Modules\Topic\Services\LessonTopicService;
use Modules\Lesson\Serializers\LessonPreviewSerializer;
use Modules\UserProgress\Modules\Lesson\Services\LessonUserProgressService;
use Modules\UserProgress\Modules\Topic\Services\TopicUserProgressService;
use Modules\UserProgress\Serializers\LinkSerializer;
use Modules\UserProgress\Services\UserProgressService;
use Neti\Laravel\Files\Serializers\FileSerializer;
use Netibackend\Laravel\Api\Serializers\JSendResponse;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class LessonTopicController extends BaseApiController
{
    /**
     * @var LessonTopicService
     */
    private LessonTopicService $topicService;
    /**
     * @var TopicUserProgressService
     */
    private TopicUserProgressService $topicProgressService;
    /**
     * @var LessonUserProgressService
     */
    private LessonUserProgressService $lessonProgressService;
    /**
     * @var UserProgressService
     */
    private UserProgressService $progressService;

    public function __construct(
        LessonTopicService $topicService,
        TopicUserProgressService $topicProgressService,
        LessonUserProgressService $lessonProgressService,
        UserProgressService $progressService
    ) {
        $this->topicService = $topicService;
        $this->topicProgressService = $topicProgressService;
        $this->lessonProgressService = $lessonProgressService;
        $this->progressService = $progressService;
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     * @throws Exception
     */
    public function topic($id): array
    {
        $topic = $this->topicService->tryGetActiveById($id);
        $this->progressService->setTopicPassedIndicator($this->getCurrentUser(), $topic);
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs
            ->add(
                (new Breadcrumb($topic->lesson_id, $topic->lesson->title))->setLinkAttr('lessonId')
            )
            ->add(
                (new Breadcrumb($topic->id, $topic->title))->setLinkAttr('topicId')
            );

        $nextLink = $this->progressService->getNextLink($this->getCurrentUser(), $topic);
        $previousLink = $this->progressService->getPreviousLink($this->getCurrentUser(), $topic);

        return [
            'topic' => LessonTopicSerializer::serialize($topic),
            'next' => LinkSerializer::serialize($nextLink),
            'previous' => LinkSerializer::serialize($previousLink),
            'breadcrumbs' => $breadcrumbs->toArray()
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws AuthenticationException
     * @throws DataValidationException
     * @throws Exception
     */
    public function pass($id): array
    {
        $topic = $this->topicService->tryGetActiveById($id);
        $this->progressService->ensureTopicOpen($this->getCurrentUser(), $topic);
        $this->topicProgressService->setPassed($this->getCurrentUser(), $topic);
        $lessonDone = false;

        if ($this->progressService->isLessonDone($this->getCurrentUser(), $topic->lesson)) {
            $this->lessonProgressService->setPassed($this->getCurrentUser(), $topic->lesson);
            $lessonDone = true;
        }
        $certificate = $this->progressService->createCertificate($this->getCurrentUser());

        return [
            'currentLessonDone' => $lessonDone,
            'currentTopic' => LessonTopicPreviewSerializer::serialize($topic),
            'certificate' => $certificate ? FileSerializer::serialize($certificate) : null
        ];
    }

}
