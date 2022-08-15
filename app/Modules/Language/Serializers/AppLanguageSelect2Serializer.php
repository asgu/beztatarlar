<?php

namespace Modules\Language\Serializers;

use Modules\Language\Models\AppLanguage;
use Netibackend\Laravel\Serializers\AbstractProperties;

class AppLanguageSelect2Serializer extends AbstractProperties
{
    public function getProperties(): array
    {
        return [
            AppLanguage::class => [
                'id'   => 'code',
                'text' => 'name'
            ],
        ];
    }
}
