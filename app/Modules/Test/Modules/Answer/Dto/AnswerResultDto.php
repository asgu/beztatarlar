<?php


namespace Modules\Test\Modules\Answer\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class AnswerResultDto extends AbstractDto
{
    public $questionId = null;
    public $answerId = null;
    public $isCorrect = false;
}
