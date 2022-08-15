<?php

namespace Modules\Language\Serializers;

use Modules\Language\Models\AppLanguage;
use Netibackend\Laravel\Serializers\AbstractProperties;

class AppLanguageSerializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            AppLanguage::class => [
                'code',
                'name'
            ],
        ];
    }
}
