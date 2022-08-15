<?php


namespace Modules\Language\Dto;

use Netibackend\Laravel\DTO\AbstractDto;

class AppLanguageMessageSaveDto extends AbstractDto
{
    public mixed $code;
    public mixed $type;
    public mixed $message_values;
}
