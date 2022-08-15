<?php


namespace Modules\Language\Factories;


use Modules\Language\Models\AppLanguageMessage;

class LanguageMessagesFactory
{
    public function create(): AppLanguageMessage
    {
        return new AppLanguageMessage();
    }
}
