<?php


namespace Modules\Language\Helpers;


use Modules\Language\Models\AppLanguageMessage;

class LanguageHelper
{
    /**
     * @return string[]
     */
    public static function getLanguageMessageTypesLabels(): array
    {
        return [
            AppLanguageMessage::TYPE_BACKEND  => 'Backend',
            AppLanguageMessage::TYPE_FRONTEND => 'Frontend',
            AppLanguageMessage::TYPE_FRONTEND_ARGUMENTS => 'Frontend agreements',
            AppLanguageMessage::TYPE_FRONTEND_PRIVACY_POLICY => 'Frontend privacy policy',
        ];
    }

    /**
     * @param AppLanguageMessage $message
     *
     * @return string
     */
    public static function getLanguageMessageTypesLabel(AppLanguageMessage $message): string
    {
        return self::getLanguageMessageTypesLabels()[$message->type] ?? $message->type;
    }
}
