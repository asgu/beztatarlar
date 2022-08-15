<?php


namespace Modules\User\Modules\Profile\Dto;


use Illuminate\Http\UploadedFile;
use Netibackend\Laravel\DTO\AbstractDto;

class UserProfileDto extends AbstractDto
{
    /** @var mixed|string */
    public $name;

    /** @var mixed|string */
    public $surname;

    /** @var mixed|string */
    public $patronymic;

    /** @var mixed|string */
    public $birthday;

    /** @var mixed|string */
    public $gender;

    /** @var mixed|string */
    public $phone;

    /** @var mixed|UploadedFile */
    public $photo;

    /** @var mixed|string */
    public $fullName;
}
