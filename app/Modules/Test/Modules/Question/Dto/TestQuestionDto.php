<?php


namespace Modules\Test\Modules\Question\Dto;


use Modules\Test\Modules\Answer\Dto\TestAnswerDto;
use Modules\Test\Modules\TestQuestionLink\Dto\TestQuestionLinkDto;
use Netibackend\Laravel\DTO\AbstractDto;

/**
 * Class TestQuestionDto
 * @package Modules\Test\Modules\Question\Dto
 * @property null|TestQuestionLinkDto $link
 * @property null|TestAnswerDto $answer
 */
class TestQuestionDto extends AbstractDto
{
    /** @var string|mixed */
    public $question;

    protected ?TestQuestionLinkDto $_link;

    protected ?TestAnswerDto $_answer;

    /**
     * @return TestQuestionLinkDto|null
     */
    public function getLink(): ?TestQuestionLinkDto
    {
        return $this->_link;
    }

    /**
     * @param $value
     */
    public function setLink($value): void
    {
        $this->_link = TestQuestionLinkDto::populateByArray($value);
    }

    /**
     * @return TestAnswerDto|null
     */
    public function getAnswer(): ?TestAnswerDto
    {
        return $this->_answer;
    }

    /**
     * @param $value
     */
    public function setAnswer($value): void
    {
        $this->_answer = TestAnswerDto::populateByArray($value);
    }

}
