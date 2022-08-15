<?php


namespace Modules\User\Modules\Profile\Validation\RulesValidation;


use Modules\User\Modules\Profile\Helpers\GenderHelper;
use Modules\User\Modules\Profile\Validation\Rules\PhoneRule;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class SocialMediaProfileValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'surname' => 'required|string',
            'patronymic' => 'nullable|string',
            'gender' => [
                'required',
                Rule::in([GenderHelper::GENDER_MALE, GenderHelper::GENDER_FEMALE, GenderHelper::GENDER_UNKNOWN])
            ],
            'phone' => [
                'nullable',
                'string',
                'max:255',
                new PhoneRule()
            ]
        ];
    }
}
