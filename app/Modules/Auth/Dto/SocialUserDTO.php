<?php

namespace Modules\Auth\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

/**
 * Class SocialUserDTO
 * @package Modules\Auth\DTO
 * @property string $provider;
 * @property string $provider_id;
 * @property string $token;
 * @property string $name;
 * @property string $email;
 * @property string $phone;
 */
class SocialUserDTO extends AbstractDTO
{
    /** @var string  */
    public string $provider;

    /** @var string  */
    public string $provider_id;

    /** @var string  */
    public string $token;

    /** @var string  */
    public string $name;

    /** @var string  */
    public string $email;

    /** @var string|null */
    public ?string $phone;
}
