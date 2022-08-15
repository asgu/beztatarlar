<?php


namespace Modules\Parish\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class ParishDto extends AbstractDto
{
    /** @var mixed|string */
    public $title;

    /** @var mixed|string */
    public $status;
}
