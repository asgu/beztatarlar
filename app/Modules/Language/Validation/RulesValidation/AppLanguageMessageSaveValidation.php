<?php

namespace Modules\Language\Validation\RulesValidation;

use Illuminate\Database\Query\Builder;
use Modules\Language\Models\AppLanguageMessage;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

/**
 * Class UserSaveValidation
 * @package Modules\User\Validation\RulesValidation
 *
 * @method AppLanguageMessage getModel()
 */
class AppLanguageMessageSaveValidation extends ModelValidator
{
    public function rules(): array
    {
        return [
            'code'           => [
                'required',
                'string',
                Rule::exists('app_languages', 'code'),
                Rule::unique('app_language_messages')->where(function(Builder $query) {
                    return $query
                        ->where('code', $this->getModel()->code)
                        ->where('type', $this->getModel()->type);
                })->ignore($this->getModel()->id)
            ],
            'type'           => [
                'required',
                Rule::in([
                    AppLanguageMessage::TYPE_BACKEND,
                    AppLanguageMessage::TYPE_FRONTEND,
                    AppLanguageMessage::TYPE_FRONTEND_ARGUMENTS,
                    AppLanguageMessage::TYPE_FRONTEND_PRIVACY_POLICY
                ])],
            'message_values' => ['required', 'json'],
            'file'           => [Rule::fileModelExtension(['html'])],
        ];
    }
}
