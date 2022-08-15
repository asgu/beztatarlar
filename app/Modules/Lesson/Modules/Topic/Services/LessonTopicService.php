<?php


namespace Modules\Lesson\Modules\Topic\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;
use Modules\Lesson\Modules\Topic\Dto\LessonTopicDto;
use Modules\Lesson\Modules\Topic\Factories\LessonTopicFactory;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\Lesson\Modules\Topic\Models\Type\AbstractTopicType;
use Modules\Lesson\Modules\Topic\Models\Type\AudioTopicTypeDecorator;
use Modules\Lesson\Modules\Topic\Models\Type\TextTopicTypeDecorator;
use Modules\Lesson\Modules\Topic\Models\Type\TopicType;
use Modules\Lesson\Modules\Topic\Models\Type\VideoTopicTypeDecorator;
use Modules\Lesson\Modules\Topic\Repositories\LessonTopicRepository;
use Modules\Lesson\Modules\Topic\Validation\RulesValidation\LessonTopicValidator;
use Neti\Laravel\Files\Services\FileService;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class LessonTopicService
{
    /**
     * @var LessonTopicRepository
     */
    private LessonTopicRepository $repository;
    /**
     * @var LessonTopicFactory
     */
    private LessonTopicFactory $factory;
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct(
        LessonTopicRepository $repository,
        LessonTopicFactory $factory,
        FileService $fileService
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->fileService = $fileService;
    }

    /**
     * @param LessonTopic $topic
     * @return AbstractTopicType
     */
    public function getTopicType(LessonTopic $topic): AbstractTopicType
    {
        $type = new TopicType();
        if ($topic->video_url) {
            $type = new VideoTopicTypeDecorator($type);
        }
        if ($topic->content_text) {
            $type = new TextTopicTypeDecorator($type);
        }
        if ($topic->audio()->exists()) {
            $type = new AudioTopicTypeDecorator($type);
        }
        return $type;
    }

    /**
     * @param Lesson|null $lesson
     * @return LessonTopic
     */
    public function createDraw(?Lesson $lesson = null): LessonTopic
    {
        return $this->factory->create($lesson);
    }

    /**
     * @param $id
     * @return LessonTopic|null
     */
    public function getById($id): ?LessonTopic
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return Builder|LessonTopic|null
     */
    public function getActiveById($id): ?LessonTopic
    {
        return $this->repository->findActiveById($id);
    }

    /**
     * @param $id
     * @return LessonTopic
     */
    public function tryGetById($id): LessonTopic
    {
        $topic = $this->getById($id);
        $this->ensureExists($topic);
        return $topic;
    }

    /**
     * @param $id
     * @return LessonTopic
     */
    public function tryGetActiveById($id): LessonTopic
    {
        $topic = $this->getActiveById($id);
        $this->ensureExists($topic);
        return $topic;
    }

    /**
     * @param Lesson $lesson
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(Lesson $lesson): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->getQuery($lesson))
            ->filterColumn('status', function (Builder $query, $status) {
                $query->where('status', $status);
            });
    }

    /**
     * @param LessonTopic $topic
     * @param LessonTopicDto $dto
     * @throws Exception
     */
    public function populate(LessonTopic $topic, LessonTopicDto $dto): void
    {
        $topic->fill($dto->toArray());
        $this->setAudio($topic, $dto->audio);
    }

    /**
     * @param LessonTopic $topic
     * @throws DataValidationException
     */
    public function tryValidate(LessonTopic $topic): void
    {
        LessonTopicValidator::validateStatic($topic, true);
    }

    /**
     * @param LessonTopic $topic
     * @throws DataValidationException
     * @throws Exception
     */
    public function save(LessonTopic $topic): void
    {
        $this->tryValidate($topic);
        $this->saveAudio($topic);
        $this->repository->save($topic);
    }

    /**
     * @param LessonTopic $topic
     * @throws DataValidationException
     */
    public function destroy(LessonTopic $topic): void
    {
        $topic->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($topic);
    }

    /**
     * @param LessonTopic $topic
     * @throws Exception
     */
    protected function saveAudio(LessonTopic $topic): void
    {
        if ($topic->audio && $topic->audio->needSaveFile) {
            $this->fileService->save($topic->audio);
            $topic->audio_uuid = $topic->audio->uuid;
        }

        if ($topic->removeAudio) {
            $this->fileService->safeRemove($topic->removeAudio);
        }
    }

    /**
     * @param LessonTopic $topic
     * @param UploadedFile|null $file
     * @throws Exception
     */
    protected function setAudio(LessonTopic $topic, ?UploadedFile $file) {
        if ($file) {
            $audio = $this->fileService->createDrawByUploadedFile($file);
            $topic->setAudio($audio);
        }
    }

    /**
     * @param LessonTopic|null $topic
     */
    protected function ensureExists(?LessonTopic $topic): void
    {
        if (!$topic) {
            throw new NotFoundException(__('app.lesson.errors.topicNotFound'));
        }
    }

    /**
     * @return int
     */
    public function getActiveTopicsCount(): int
    {
        return $this->repository->findActiveCount();
    }

    /**
     * @param Lesson $lesson
     * @return Collection
     */
    public function getAllActiveByLesson(Lesson $lesson): Collection
    {
        return $this->repository->findAllActiveByLesson($lesson);
    }

    /**
     * @param $lessonId
     * @param $priority
     * @param $ignoreId
     * @return int
     */
    public function getCountByLessonAndPriority($lessonId, $priority, $ignoreId): int
    {
        return $this->repository->findActiveCountByLessonAndPriority($lessonId, $priority, $ignoreId);
    }

    /**
     * @param LessonTopic $topic
     * @return LessonTopic|null
     */
    public function getNextTopic(LessonTopic $topic): ?LessonTopic
    {
        return $this->repository->findNext($topic);
    }

    /**
     * @param LessonTopic $topic
     * @return Builder|Model|LessonTopic|object|null
     */
    public function getPreviousTopic(LessonTopic $topic): ?LessonTopic
    {
        return $this->repository->findPrevious($topic);
    }

    /**
     * @param Lesson|null $lesson
     * @return LessonTopic|null
     */
    public function getFirstTopic(?Lesson $lesson): ?LessonTopic
    {
        if (!$lesson) {
            return null;
        }
        return $this->repository->findFirstActiveByLesson($lesson);
    }

    /**
     * @param Lesson|null $lesson
     * @return Builder|Model|LessonTopic|object|null
     */
    public function getLastTopic(?Lesson $lesson): ?LessonTopic
    {
        if (!$lesson) {
            return null;
        }
        return $this->repository->findLastActiveByLesson($lesson);
    }
}
