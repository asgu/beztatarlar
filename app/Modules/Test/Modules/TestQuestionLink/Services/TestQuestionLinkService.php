<?php


namespace Modules\Test\Modules\TestQuestionLink\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Modules\Test\Modules\TestQuestionLink\Dto\TestQuestionLinkDto;
use Modules\Test\Modules\TestQuestionLink\Factory\TestQuestionLinkFactory;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;
use Modules\Test\Modules\TestQuestionLink\Repositories\TestQuestionLinkRepository;
use Modules\Test\Modules\TestQuestionLink\Validation\RulesValidation\TestQuestionLinkValidator;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class TestQuestionLinkService
{
    /**
     * @var TestQuestionLinkFactory
     */
    private TestQuestionLinkFactory $factory;
    /**
     * @var TestQuestionLinkRepository
     */
    private TestQuestionLinkRepository $repository;

    public function __construct(
        TestQuestionLinkFactory $factory,
        TestQuestionLinkRepository $repository
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param Test|null $test
     * @return TestQuestionLink
     */
    public function createDraw(?Test $test): TestQuestionLink
    {
        return $this->factory->create($test);
    }

    /**
     * @param Test $test
     * @param TestQuestion $question
     * @return TestQuestionLink|null
     */
    public function getByTestAndQuestion(Test $test, TestQuestion $question): ?TestQuestionLink
    {
        return $this->repository->findByTestAndQuestion($test, $question);
    }

    /**
     * @param Test $test
     * @param TestQuestion $question
     * @return TestQuestionLink
     */
    public function tryGetByTestAndQuestion(Test $test, TestQuestion $question): TestQuestionLink
    {
        $link = $this->getByTestAndQuestion($test, $question);
        $this->ensureExists($link);
        return $link;
    }

    /**
     * @param Test $test
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(Test $test): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->getQuery($test))
            ->filterColumn('question', function (Builder $query, $question) {
                $query->whereHas('question', function (Builder $subQuery) use ($question) {
                    $subQuery->where('question', 'LIKE', "%$question%");
                });
            });
    }

    /**
     * @param TestQuestionLink $link
     * @param TestQuestionLinkDto|null $dto
     * @throws Exception
     */
    public function populate(TestQuestionLink $link, ?TestQuestionLinkDto $dto): void
    {
        if ($dto) {
            $link->fill($dto->toArray());
        }
    }

    /**
     * @param TestQuestionLink $link
     * @throws DataValidationException
     */
    public function tryValidate(TestQuestionLink $link): void
    {
        TestQuestionLinkValidator::validateStatic($link);
    }

    /**
     * @param TestQuestionLink $link
     * @throws DataValidationException
     */
    public function save(TestQuestionLink $link): void
    {
        $this->tryValidate($link);
        $this->repository->save($link);
    }

    /**
     * @param Test $test
     * @param TestQuestion $question
     */
    public function destroy(Test $test, TestQuestion $question)
    {
        $link = $this->tryGetByTestAndQuestion($test, $question);
        $this->repository->delete($link);
    }

    /**
     * @param TestQuestionLink|null $link
     */
    private function ensureExists(?TestQuestionLink $link)
    {
        if (!$link) {
            throw new NotFoundException(__('app.test.question.errors.notFound'));
        }
    }

    /**
     * @param $testId
     * @param $priority
     * @param $ignoreId
     * @return int
     */
    public function getCountByTestAndPriority($testId, $priority, $ignoreId): int
    {
        return $this->repository->findCountByTestAndPriority($testId, $priority, $ignoreId);
    }

}
