<?php


namespace Modules\User\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class ChangeEmailDto extends AbstractDto
{
    /** @var mixed|string */
    public $email;

    /** @var mixed|string */
    public $password;

}
