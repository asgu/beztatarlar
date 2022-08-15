<?php


namespace Modules\Test\Modules\Answer\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class AnswerDto extends AbstractDto
{
    public $questionId;
    public $answerId;
}
