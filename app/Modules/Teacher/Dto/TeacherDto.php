<?php


namespace Modules\Teacher\Dto;


use Illuminate\Http\UploadedFile;
use Netibackend\Laravel\DTO\AbstractDto;

class TeacherDto extends AbstractDto
{
    /** @var mixed|string */
    public $name;

    /** @var mixed|string */
    public $description;

    /** @var mixed|UploadedFile */
    public $photo;

    /** @var mixed|string */
    public $status;
}
