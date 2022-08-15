<?php


namespace Modules\Test\Modules\Question\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Question\Dto\TestQuestionDto;
use Modules\Test\Modules\Question\Factories\TestQuestionFactory;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Modules\Test\Modules\Question\Repositories\TestQuestionRepository;
use Modules\Test\Modules\Question\Validation\RulesValidation\TestQuestionValidator;
use Netibackend\Laravel\DTO\AbstractDto;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\Facades\DataTables;

class TestQuestionService
{
    /**
     * @var TestQuestionRepository
     */
    private TestQuestionRepository $repository;
    /**
     * @var TestQuestionFactory
     */
    private TestQuestionFactory $factory;

    public function __construct(TestQuestionRepository $repository, TestQuestionFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @return TestQuestion
     */
    public function createDraw(): TestQuestion
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @return TestQuestion|null
     */
    public function getById($id): ?TestQuestion
    {
        return $this->repository->findById($id);
    }

    /**
     * @param $id
     * @return TestQuestion
     */
    public function tryGetById($id): TestQuestion
    {
        $question = $this->getById($id);
        $this->ensureExists($question);
        return $question;
    }

    /**
     * @param TestQuestion $question
     * @param TestQuestionDto $dto
     * @throws DataValidationException
     * @throws Exception
     */
    public function update(TestQuestion $question, TestQuestionDto $dto)
    {
        $this->populate($question, $dto);
        $this->save($question);
    }

    /**
     * @param TestQuestion $question
     * @param TestQuestionDto $dto
     * @throws Exception
     */
    public function populate(TestQuestion $question, TestQuestionDto $dto): void
    {
        $question->fill($dto->toArray());
    }

    /**
     * @param TestQuestion $question
     * @throws DataValidationException
     */
    public function tryValidate(TestQuestion $question): void
    {
        TestQuestionValidator::validateStatic($question);
    }

    /**
     * @param TestQuestion $question
     * @throws DataValidationException
     */
    public function save(TestQuestion $question): void
    {
        $this->tryValidate($question);
        $this->repository->save($question);
    }

    /**
     * @param TestQuestion $question
     */
    public function destroy(TestQuestion $question): void
    {
        $this->repository->delete($question);
    }

    /**
     * @param TestQuestion|null $question
     */
    protected function ensureExists(?TestQuestion $question): void
    {
        if (!$question) {
            throw new NotFoundException(__('app.test.question.errors.notFound'));
        }
    }
}
