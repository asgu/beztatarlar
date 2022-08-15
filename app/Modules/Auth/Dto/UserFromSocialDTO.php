<?php

namespace Modules\Auth\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

/**
 * Class UserFromSocialDTO
 * @package Modules\Auth\DTO
 * @property string $name;
 * @property string $email;
 * @property string $phone;
 */
class UserFromSocialDTO extends AbstractDTO
{
    /** @var string  */
    public string $name;

    /** @var string  */
    public string $email;

    /** @var string|null */
    public ?string $phone;

    /** @var string|null */
    public ?string $email_verified_at;

    /** @var string  */
    public string $password;
}
