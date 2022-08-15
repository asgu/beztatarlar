<?php


namespace Modules\Test\Modules\Question\Dto;


use Modules\Test\Modules\Answer\Dto\AnswerDto;
use Netibackend\Laravel\DTO\AbstractDto;

/**
 * Class QuestionListDto
 * @package Modules\Test\Modules\Question\Dto
 * @property AnswerDto[] $questions
 */
class QuestionListDto extends AbstractDto
{
    /**
     * @var AnswerDto[]
     */
    protected array $_questions = [];

    /**
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->_questions;
    }

    /**
     * @param $value
     */
    public function setQuestions($value): void
    {
        foreach ($value as $answers) {
            $this->_questions[] = AnswerDto::populateByArray($answers);
        }
    }

}
