<?php

namespace Modules\Language\Serializers;

use Modules\Language\Models\AppLanguageMessage;
use Netibackend\Laravel\Modules\File\Models\File;
use Netibackend\Laravel\Modules\File\Serializers\FileSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class AppLanguageMessageAgreementsSerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            AppLanguageMessage::class => [
                'file'
            ],
            File::class => FileSerializer::class
        ];
    }
}
