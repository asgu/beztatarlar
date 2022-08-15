<?php


namespace Modules\Certificate\Dto;


use Illuminate\Http\UploadedFile;
use Netibackend\Laravel\DTO\AbstractDto;

class CertificateDto extends AbstractDto
{
    /** @var mixed|UploadedFile */
    public $file;
}
