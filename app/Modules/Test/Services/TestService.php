<?php


namespace Modules\Test\Services;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Services\LessonService;
use Modules\LessonTest\Models\LessonTest;
use Modules\LessonTest\Services\LessonTestService;
use Modules\Test\Dto\TestDto;
use Modules\Test\Dto\TestResultDto;
use Modules\Test\Factories\TestFactory;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Answer\Dto\AnswerResultDto;
use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Answer\Services\TestAnswerService;
use Modules\Test\Modules\Question\Dto\QuestionListDto;
use Modules\Test\Modules\Question\Dto\TestQuestionDto;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Modules\Test\Modules\Question\Services\TestQuestionService;
use Modules\Test\Modules\TestQuestionLink\Services\TestQuestionLinkService;
use Modules\Test\Repositories\TestRepository;
use Modules\Test\Validation\RulesValidation\TestValidator;
use Netibackend\Laravel\Exceptions\NotFoundException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Yajra\DataTables\EloquentDatatable;
use Yajra\DataTables\Facades\DataTables;

class TestService
{
    /**
     * @var TestRepository
     */
    private TestRepository $repository;
    /**
     * @var TestFactory
     */
    private TestFactory $factory;
    /**
     * @var LessonTestService
     */
    private LessonTestService $lessonTestService;
    /**
     * @var LessonService
     */
    private LessonService $lessonService;
    /**
     * @var TestQuestionService
     */
    private TestQuestionService $questionService;
    /**
     * @var TestQuestionLinkService
     */
    private TestQuestionLinkService $linkService;
    /**
     * @var TestAnswerService
     */
    private TestAnswerService $answerService;

    public function __construct(
        TestRepository $repository,
        TestFactory $factory,
        LessonTestService $lessonTestService,
        LessonService $lessonService,
        TestQuestionService $questionService,
        TestQuestionLinkService $linkService,
        TestAnswerService $answerService
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->lessonTestService = $lessonTestService;
        $this->lessonService = $lessonService;
        $this->questionService = $questionService;
        $this->linkService = $linkService;
        $this->answerService = $answerService;
    }

    /**
     * @return Test
     */
    public function createDraw(): Test
    {
        return $this->factory->create();
    }

    /**
     * @param $id
     * @param array|null $links
     * @return Test|null
     */
    public function getById($id, ?array $links = null): ?Test
    {
        return $this->repository->findById($id, $links);
    }

    /**
     * @param $id
     * @param array|null $links
     * @return Test
     */
    public function tryGetById($id, ?array $links = null): Test
    {
        $test = $this->getById($id, $links);
        $this->ensureExists($test);
        return $test;
    }

    /**
     * @param int $id
     * @return Test
     */
    public function tryGetFullTestById(int $id): Test
    {
        return $this->tryGetById(
            $id,
            ['questionLinks', 'questionLinks.question', 'questionLinks.question.answers']
        );
    }

    /**
     * @return EloquentDatatable
     */
    public function getFilteredDataTable(): EloquentDatatable
    {
        return DataTables::eloquent($this->repository->getQuery())
            ->filterColumn('status', function (Builder $query, $status) {
                $query->where('tests.status', $status);
            })
            ->filterColumn('lesson', function (Builder $query, $lesson) {
                $ids = $this->lessonService->getAllByTitle($lesson)->pluck('id');
                $query->whereHas('activeLessonTest', function (Builder $subQuery) use ($ids) {
                    $subQuery->whereIn('lesson_id', $ids);
                });
            });
    }

    /**
     * @param Test $test
     * @param TestDto $dto
     * @throws Exception
     */
    public function populate(Test $test, TestDto $dto): void
    {
        $test->fill($dto->toArray());
        $this->setActiveLessonTest($test);
        $this->lessonTestService->populate($test->activeLessonTest, $dto->lesson);
    }

    /**
     * @param Test $test
     * @throws DataValidationException
     */
    public function tryValidate(Test $test): void
    {
        TestValidator::validateStatic($test);
    }

    /**
     * @param Test $test
     * @throws DataValidationException
     * @throws Exception
     */
    public function save(Test $test): void
    {
        $this->tryValidate($test);
        $lessonTest = $test->activeLessonTest;
        DB::beginTransaction();
        try {
            $this->saveModel($test);
            $this->saveLessonTest($test, $lessonTest);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * @param $id
     * @throws DataValidationException
     */
    public function destroyById($id): void
    {
        $test = $this->getById($id);
        if (!$test) {
            return;
        }
        $test->status = ActivityStatusFacade::STATUS_INACTIVE;
        $this->save($test);
    }

    /**
     * @param Test $test
     * @param TestQuestion $question
     * @param TestQuestionDto $dto
     * @throws DataValidationException
     * @throws Exception
     */
    public function attachQuestion(Test $test, TestQuestion $question, TestQuestionDto $dto): void
    {
        $link = $this->linkService->createDraw($test);
        $answer = $this->answerService->createCorrectDraw();
        $this->linkService->populate($link, $dto->link);
        $this->questionService->populate($question, $dto);
        $this->answerService->populate($answer, $dto->answer);

        DB::beginTransaction();

        try {
            $this->questionService->save($question);
            $link->question_id = $question->id;
            $answer->question_id = $question->id;
            $this->linkService->save($link);
            $this->answerService->save($answer);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param TestQuestion $question
     * @param TestQuestionDto $dto
     * @throws DataValidationException
     * @throws Exception
     */
    public function updateQuestion(TestQuestion $question, TestQuestionDto $dto): void
    {
        $this->questionService->populate($question, $dto);
        $this->linkService->populate($question->link, $dto->link);

        DB::beginTransaction();
        try {
            $this->linkService->save($question->link);
            $this->questionService->save($question);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param Test $test
     */
    protected function setActiveLessonTest(Test $test): void
    {
        if (!$test->activeLessonTest) {
            $lessonTest = $this->lessonTestService->createDraw();
            $test->setRelation('activeLessonTest', $lessonTest);
        }
    }

    /**
     * @param Test $test
     * @param LessonTest|null $lessonTest
     */
    protected function saveLessonTest(Test $test, ?LessonTest $lessonTest): void
    {
        if ($lessonTest) {
            $this->lessonTestService->destroyAllByTest($test, $lessonTest);
            $lessonTest->test_id = $test->id;
            $lessonTest->status = $test->status;
            $this->lessonTestService->save($lessonTest);
        }
    }

    /**
     * @param Test $test
     */
    protected function saveModel(Test $test): void
    {
        $this->repository->save($test);
    }

    /**
     * @param Test|null $test
     */
    protected function ensureExists(?Test $test): void
    {
        if (!$test) {
            throw new NotFoundException(__('app.test.errors.notFound'));
        }
    }

    /**
     * @param Test $test
     * @param TestQuestion $question
     * @throws Exception
     */
    public function destroyQuestion(Test $test, TestQuestion $question)
    {
        DB::beginTransaction();
        try {
            $this->linkService->destroy($test, $question);
            $this->answerService->destroyByQuestion($question);
            $this->questionService->destroy($question);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param Test $test
     * @param QuestionListDto $questions
     * @return TestResultDto
     */
    public function checkTest(Test $test, QuestionListDto $questions): TestResultDto
    {
        $correctAnswers = $this->answerService->getAllCorrectByTest($test);
        $result = TestResultDto::populateByArray(['isCorrect' => true]);

        foreach ($correctAnswers->all() as $correct) {
            $answerResult = $this->checkAnswer($correct, $questions);
            $result->addAnswer($answerResult);
            $result->isCorrect = $result->isCorrect && $answerResult->isCorrect;
        }

        return $result;
    }

    /**
     * @param TestAnswer $correct
     * @param QuestionListDto $questions
     * @return AnswerResultDto
     */
    private function checkAnswer(TestAnswer $correct, QuestionListDto $questions): AnswerResultDto
    {
        $answerDto = new AnswerResultDto();
        $answerDto->questionId = $correct->question_id;
        foreach ($questions->questions as $question) {
            if ($question->questionId == $correct->question_id) {
                $answerDto->answerId = $question->answerId;
                $answerDto->isCorrect = $question->answerId == $correct->id;
                break;
            }
        }

        return $answerDto;
    }
}
