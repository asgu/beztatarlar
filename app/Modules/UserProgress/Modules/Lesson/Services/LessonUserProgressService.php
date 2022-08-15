<?php


namespace Modules\UserProgress\Modules\Lesson\Services;


use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Services\LessonService;
use Modules\User\Models\User;
use Modules\UserProgress\Modules\Lesson\Factories\LessonUserProgressFactory;
use Modules\UserProgress\Modules\Lesson\Models\LessonUserProgress;
use Modules\UserProgress\Modules\Lesson\Repositories\LessonUserProgressRepository;
use Modules\UserProgress\Modules\Lesson\Validation\RulesValidation\LessonUserProgressValidator;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class LessonUserProgressService
{
    /**
     * @var LessonUserProgressRepository
     */
    private LessonUserProgressRepository $repository;
    /**
     * @var LessonUserProgressFactory
     */
    private LessonUserProgressFactory $factory;
    /**
     * @var LessonService
     */
    private LessonService $lessonService;

    public function __construct(
        LessonUserProgressRepository $repository,
        LessonUserProgressFactory $factory,
        LessonService $lessonService
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->lessonService = $lessonService;
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return LessonUserProgress
     * @throws Exception
     */
    public function createDraw(User $user, Lesson $lesson): LessonUserProgress
    {
        return $this->factory->create($user, $lesson);
    }

    /**
     * @param User $user
     * @param array $lessons
     */
    public function unblockLessons(User $user, array $lessons): void
    {
        $progress = $this->prepareProgress($user->lessonProgress);

        foreach ($lessons as $key => $lesson) {
            $previous = isset($lessons[$key - 1]) ? $lessons[$key - 1] : null;
            if (!$previous) {
                $previous = $this->lessonService->getPreviousLesson($lesson);
            }

            if (!$previous) {
                $lesson->setBlocked(false);
                continue;
            }
            if ($this->isPassed($previous, $progress)) {
                /** @var Lesson $lesson */
                $lesson->setBlocked(false);
            }

            if (!$this->isPassed($lesson, $progress)) {
                break;
            }
        }
    }

    /**
     *
     * returns [<lessonId> => true, <lessonId> => true, ...]
     *
     * @param Collection $userLessonsProgress
     * @return array
     */
    private function prepareProgress(Collection $userLessonsProgress): array
    {
        $progress = [];
        /** @var LessonUserProgress $item */
        foreach ($userLessonsProgress->all() as $item) {
            $progress[$item->lesson_id] = true;
        }
        return $progress;
    }

    /**
     * @param Lesson $lesson
     * @param array $progress
     * @return bool
     */
    private function isPassed(Lesson $lesson, array $progress): bool
    {
        if (isset($progress[$lesson->id])) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    public function isLessonPassed(User $user, Lesson $lesson): bool
    {
        $lessonProgress = $this->getByUserAndLesson($user, $lesson);
        return $lessonProgress ? true : false;
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return Model|LessonUserProgress|null
     */
    public function getByUserAndLesson(User $user, Lesson $lesson)
    {
        return $this->repository->findByUserAndLesson($user, $lesson);
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @throws DataValidationException
     * @throws Exception
     */
    public function setPassed(User $user, Lesson $lesson)
    {
        if ($this->isLessonPassed($user, $lesson)) {
            return;
        }
        $progress = $this->createDraw($user, $lesson);
        $this->save($progress);
    }

    /**
     * @param LessonUserProgress $progress
     * @throws DataValidationException
     */
    private function save(LessonUserProgress $progress)
    {
        $this->tryValidate($progress);
        $this->repository->save($progress);
    }

    /**
     * @param LessonUserProgress $progress
     * @throws DataValidationException
     */
    private function tryValidate(LessonUserProgress $progress)
    {
        LessonUserProgressValidator::validateStatic($progress);
    }

    /**
     * @param User $user
     * @return int|null
     */
    public function getPassedMaxPriority(User $user): ?int
    {
        return $this->repository->findMaxPassedPriority($user);
    }

}
