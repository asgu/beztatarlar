<?php


namespace Modules\Certificate\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class TemplateParamsDto extends AbstractDto
{
    public int $x;
    public int $y;
    public int $width;
    public int $height;
    public bool $adjustPageSize;
}
