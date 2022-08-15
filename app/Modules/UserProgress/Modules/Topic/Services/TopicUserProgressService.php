<?php


namespace Modules\UserProgress\Modules\Topic\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\User\Models\User;
use Modules\UserProgress\Modules\Topic\Factories\TopicUserProgressFactory;
use Modules\UserProgress\Modules\Topic\Models\TopicUserProgress;
use Modules\UserProgress\Modules\Topic\Repositories\TopicUserProgressRepository;
use Modules\UserProgress\Modules\Topic\Validation\RulesValidation\TopicUserProgressValidator;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TopicUserProgressService
{
    /**
     * @var TopicUserProgressRepository
     */
    private TopicUserProgressRepository $repository;
    /**
     * @var TopicUserProgressFactory
     */
    private TopicUserProgressFactory $factory;

    public function __construct(TopicUserProgressRepository $repository, TopicUserProgressFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return TopicUserProgress
     * @throws Exception
     */
    public function createDraw(User $user, LessonTopic $topic): TopicUserProgress
    {
        return $this->factory->create($user, $topic);
    }

    /**
     * @param User $user
     * @return int
     */
    public function getActivePassedTopicsCount(User $user): int
    {
        return $this->repository->findActivePassedByUser($user);
    }

    /**
     * @param User $user
     * @param Lesson[] $lessons
     * @return Collection
     */
    public function getTopicsByUserAndLessons(User $user, array $lessons): Collection
    {
        return $this->repository->findActivePassedByUserAndLessons($user, $lessons);
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @throws DataValidationException
     * @throws Exception
     */
    public function setPassed(User $user, LessonTopic $topic)
    {
        $topic->setPassed(true);
        if ($this->isTopicPassedByUser($user, $topic)) {
            return;
        }
        $progress = $this->createDraw($user, $topic);
        $this->save($progress);
    }

    /**
     * @param TopicUserProgress $progress
     * @throws DataValidationException
     */
    private function save(TopicUserProgress $progress)
    {
        $this->tryValidate($progress);
        $this->repository->save($progress);
    }

    /**
     * @param TopicUserProgress $progress
     * @throws DataValidationException
     */
    private function tryValidate(TopicUserProgress $progress): void
    {
        TopicUserProgressValidator::validateStatic($progress);
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return Builder|Model|TopicUserProgress|null
     */
    private function getByUserAndTopic(User $user, LessonTopic $topic): ?TopicUserProgress
    {
        return $this->repository->findByUserAndTopic($user, $topic);
    }

    /**
     * @param User $user
     * @param Collection $activeTopics
     * @return Collection
     */
    public function getAllByUserAndTopics(User $user, Collection $activeTopics): Collection
    {
        return $this->repository->findAllByUserAndTopics($user, $activeTopics);
    }

    /**
     * @param User $user
     * @param LessonTopic $topic
     * @return bool
     */
    public function isTopicPassedByUser(User $user, LessonTopic $topic): bool
    {
        return $this->getByUserAndTopic($user, $topic) ? true : false;
    }

}
