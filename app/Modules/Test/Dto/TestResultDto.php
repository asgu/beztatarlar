<?php


namespace Modules\Test\Dto;


use Modules\Test\Modules\Answer\Dto\AnswerResultDto;
use Netibackend\Laravel\DTO\AbstractDto;

/**
 * Class TestResultDto
 * @package Modules\Test\Dto
 * @property AnswerResultDto[] $answers
 */
class TestResultDto extends AbstractDto
{
    /** @var bool */
    public bool $isCorrect = true;

    /** @var AnswerResultDto[]  */
    protected array $_answers = [];

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->_answers;
    }

    public function addAnswer(AnswerResultDto $answer): void
    {
        $this->_answers[] = $answer;
    }
}
