<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Dto\SearchDto;
use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Modules\Lesson\Serializers\LessonListSerializer;
use Modules\Lesson\Services\LessonService;
use Modules\UserProgress\Modules\Lesson\Services\LessonUserProgressService;
use Modules\UserProgress\Services\UserProgressService;
use Netibackend\Laravel\SearchProvider\Serializers\PaginationMetaSerializer;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;


class LessonController extends BaseApiController
{
    /**
     * @var LessonService
     */
    private LessonService $lessonService;
    /**
     * @var LessonUserProgressService
     */
    private LessonUserProgressService $lessonUserProgressService;
    /**
     * @var UserProgressService
     */
    private UserProgressService $progressService;

    public function __construct(
        LessonService $lessonService,
        LessonUserProgressService $lessonUserProgressService,
        UserProgressService $progressService
    )
    {
        $this->lessonService = $lessonService;
        $this->lessonUserProgressService = $lessonUserProgressService;
        $this->progressService = $progressService;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthenticationException
     * @throws BindingResolutionException
     * @throws DataValidationException
     * @throws Exception
     */
    public function getList(Request $request): array
    {
        $dto = SearchDto::populateByArray($request->all());
        $providerResult = $this->lessonService->getAllActive($dto);
        $lessons = $providerResult->getModels();
        $this->lessonUserProgressService->unblockLessons($this->getCurrentUser(), $lessons);
        $this->progressService->prepareLessons($this->getCurrentUser(), $lessons);

        return [
            'items'      => LessonListSerializer::serialize($lessons),
            'pagination' => PaginationMetaSerializer::serialize($providerResult->getPaginationMeta())
        ];
    }

    /**
     * @return array
     * @throws AuthenticationException
     */
    public function progress(): array
    {
        $user = $this->getCurrentUser();
        return [
            'progress' => $this->progressService->calculateProgress($user)
        ];
    }
}
