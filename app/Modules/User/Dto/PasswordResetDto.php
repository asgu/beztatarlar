<?php


namespace Modules\User\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class PasswordResetDto extends AbstractDto
{
    /** @var mixed|string */
    public $password;

    /** @var mixed|string */
    public $repeatPassword;
}
