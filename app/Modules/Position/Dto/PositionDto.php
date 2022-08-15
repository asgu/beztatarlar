<?php


namespace Modules\Position\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class PositionDto extends AbstractDto
{
    /** @var mixed|string */
    public $title;

    /** @var mixed|string */
    public $status;
}
