<?php


namespace Modules\Test\Modules\Answer\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Answer\Dto\TestAnswerDto;
use Modules\Test\Modules\Answer\Exceptions\CantDeleteException;
use Modules\Test\Modules\Answer\Factories\TestAnswerFactory;
use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Answer\Repositories\TestAnswerRepository;
use Modules\Test\Modules\Answer\Validation\RulesValidation\TestAnswerValidator;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TestAnswerService
{
    /**
     * @var TestAnswerFactory
     */
    private TestAnswerFactory $factory;
    /**
     * @var TestAnswerRepository
     */
    private TestAnswerRepository $repository;

    public function __construct(TestAnswerFactory $factory, TestAnswerRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @return TestAnswer
     */
    public function createCorrectDraw(): TestAnswer
    {
        return $this->factory->create(true);
    }

    /**
     * @param $id
     * @return TestAnswer|null
     */
    public function getById($id): ?TestAnswer
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return TestAnswer
     */
    public function tryGetById($id): TestAnswer
    {
        $answer = $this->getById($id);
        $this->ensureExists($answer);
        return $answer;
    }

    /**
     * @param TestQuestion $question
     * @return TestAnswer
     */
    public function createIncorrectDraw(TestQuestion $question): TestAnswer
    {
        return $this->factory->createByQuestion($question);
    }

    /**
     * @param TestAnswer $answer
     * @param TestAnswerDto $dto
     * @throws Exception
     */
    public function populate(TestAnswer $answer, TestAnswerDto $dto): void
    {
        $answer->fill($dto->toArray());
    }

    /**
     * @param TestAnswer $answer
     * @throws DataValidationException
     */
    public function tryValidate(TestAnswer $answer): void
    {
        TestAnswerValidator::validateStatic($answer);
    }

    /**
     * @param TestAnswer $answer
     * @throws DataValidationException
     */
    public function save(TestAnswer $answer): void
    {
        $this->tryValidate($answer);
        $this->repository->save($answer);
    }

    /**
     * @param $questionId
     * @return Builder
     */
    public function getQuery($questionId): Builder
    {
        return $this->repository->getQuery($questionId);
    }

    /**
     * @param TestQuestion $question
     */
    public function destroyByQuestion(TestQuestion $question)
    {
        $this->repository->deleteByQuestion($question);
    }

    /**
     * @param $id
     */
    public function destroyById($id)
    {
        $answer = $this->getById($id);
        if (!$answer) {
            return;
        }
        $this->ensureCanDelete($answer);
        $this->repository->delete($answer);
    }

    /**
     * @param TestAnswer $answer
     * @return bool
     */
    public function canDelete(TestAnswer $answer): bool
    {
        return $answer->is_correct ? false : true;
    }

    /**
     * @param TestAnswer|null $answer
     */
    private function ensureExists(?TestAnswer $answer): void
    {
        if (!$answer) {
            throw new NotFoundException(__('app.test.answer.errors.notFound'));
        }
    }

    /**
     * @param TestAnswer $answer
     * @throws CantDeleteException
     */
    private function ensureCanDelete(TestAnswer $answer): void
    {
        if (!$this->canDelete($answer)) {
            throw new CantDeleteException(__('app.test.answer.errors.cantDelete'));
        }
    }

    /**
     * @param Test $test
     * @return Collection
     */
    public function getAllCorrectByTest(Test $test): Collection
    {
        return $this->repository->findAllCorrect($test);
    }

}
