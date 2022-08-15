<?php


namespace Modules\User\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class ChangePasswordDto extends AbstractDto
{
    /** @var Mixed|string */
    public $currentPassword;

    /** @var Mixed|string */
    public $newPassword;

    /** @var Mixed|string */
    public $repeatNewPassword;
}
