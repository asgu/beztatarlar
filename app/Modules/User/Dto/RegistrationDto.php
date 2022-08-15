<?php


namespace Modules\User\Dto;


use Modules\User\Modules\Profile\Dto\UserProfileDto;

/**
 * Class RegistrationDto
 * @package Modules\User\Dto
 * @property UserProfileDto $profile
 */
class RegistrationDto extends UserDto
{
    /** @var mixed|string */
    public $email;

    /** @var mixed|string */
    public $password;

    /** @var mixed|string */
    public $repeatPassword;

    /** @var mixed|bool */
    public $agreement;

}
