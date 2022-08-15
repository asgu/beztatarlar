<?php


namespace Modules\User\Modules\Profile\Validation\RulesValidation;


use Modules\File\Validation\Rules\FileModelExtensionRule;
use Modules\User\Modules\Profile\Helpers\GenderHelper;
use Modules\User\Modules\Profile\Validation\Rules\BirthDateRule;
use Modules\User\Modules\Profile\Validation\Rules\NameRule;
use Modules\User\Modules\Profile\Validation\Rules\PhoneRule;
use Neti\Laravel\Files\Helpers\FileTypesHelper;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class UserProfileValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                new NameRule()
            ],
            'surname' => [
                'required',
                'string',
                new NameRule()
            ],
            'patronymic' => [
                'nullable',
                'string',
                new NameRule()
            ],
            'birthday' => [
                'required',
                'string',
                'date_format:Y-m-d',
                new BirthDateRule()
            ],
            'gender' => [
                'required',
                Rule::in([GenderHelper::GENDER_MALE, GenderHelper::GENDER_FEMALE, GenderHelper::GENDER_UNKNOWN])
            ],
            'phone' => [
                'nullable',
                'string',
                'max:255',
                new PhoneRule()
            ],
            'photo' => [
                'sometimes',
                new FileModelExtensionRule(FileTypesHelper::ALLOW_IMAGES_EXTENSIONS)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'birthday.date_format' => __('validation.dateFormat', ['format' => __('columns.dateFormat.ddMmYyyy')])
        ];
    }
}
