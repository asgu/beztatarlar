<?php

namespace Modules\Language\Serializers;

use Modules\Language\Models\AppLanguage;
use Modules\Language\Models\AppLanguageMessage;
use Netibackend\Laravel\Serializers\AbstractProperties;

class AppLanguageMessageSerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            AppLanguageMessage::class => [
                'language' => 'appLanguage',
                'values'   => 'message_values'
            ],
            AppLanguage::class        => AppLanguageSerializer::class
        ];
    }
}
