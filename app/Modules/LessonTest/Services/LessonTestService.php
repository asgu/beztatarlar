<?php


namespace Modules\LessonTest\Services;


use Exception;
use Modules\LessonTest\Dto\LessonDto;
use Modules\LessonTest\Factories\LessonTestFactory;
use Modules\LessonTest\Models\LessonTest;
use Modules\LessonTest\Repositories\LessonTestRepository;
use Modules\LessonTest\Validation\RulesValidation\LessonTestValidator;
use Modules\Test\Models\Test;
use Netibackend\Laravel\Exceptions\NotFoundException;

class LessonTestService
{
    /**
     * @var LessonTestFactory
     */
    private LessonTestFactory $factory;
    /**
     * @var LessonTestRepository
     */
    private LessonTestRepository $repository;

    public function __construct(LessonTestFactory $factory, LessonTestRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @return LessonTest
     */
    public function createDraw(): LessonTest
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return LessonTest|null
     */
    public function getById($id): ?LessonTest
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return LessonTest|null
     */
    public function getActiveById($id): ?LessonTest
    {
        return $this->repository->findActiveById($id);
    }

    /**
     * @param $id
     * @return LessonTest
     * @throws NotFoundException
     */
    public function tryGetActiveById($id): LessonTest
    {
        $test = $this->getActiveById($id);
        $this->ensureExists($test);
        return $test;
    }

    /**
     * @param LessonTest $lessonTest
     * @param LessonDto|null $dto
     * @throws Exception
     */
    public function populate(LessonTest $lessonTest, ?LessonDto $dto): void
    {
        if ($dto) {
            $lessonTest->fill($dto->toArray());
        }
    }

    /**
     * @param LessonTest $lessonTest
     */
    public function tryValidate(LessonTest $lessonTest): void
    {
        LessonTestValidator::validateStatic($lessonTest);
    }

    /**
     * @param LessonTest $lessonTest
     */
    public function save(LessonTest $lessonTest): void
    {
        $this->tryValidate($lessonTest);
        $this->saveModel($lessonTest);
    }

    /**
     * @param Test $test
     * @param LessonTest $exceptTest
     */
    public function destroyAllByTest(Test $test, LessonTest $exceptTest): void
    {
        $this->repository->setInactiveByTest($test, $exceptTest);
    }

    /**
     * @param LessonTest $lessonTest
     */
    protected function saveModel(LessonTest $lessonTest): void
    {
        $this->repository->save($lessonTest);
    }

    /**
     * @return int
     */
    public function getActiveTestsCount(): int
    {
        return $this->repository->findActiveTestsCount();
    }

    /**
     * @param LessonTest|null $test
     * @throws NotFoundException
     */
    private function ensureExists(?LessonTest $test)
    {
        if (!$test) {
            throw new NotFoundException(__('app.test.errors.notFound'));
        }
    }
}
