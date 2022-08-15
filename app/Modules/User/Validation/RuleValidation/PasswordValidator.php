<?php


namespace Modules\User\Validation\RuleValidation;


use Netibackend\Laravel\Validation\DataValidator;

class PasswordValidator extends DataValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8'],
            'repeatPassword' => ['required', 'string', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'repeatPassword.same' => __('validation.same', ['field' => __('columns.users.password')]),
            'password.required' => __('validation.requiredField', ['field' => __('columns.users.password')]),
            'repeatPassword.required' => __('validation.requiredField', ['field' => __('columns.users.repeatPassword')]),
            'password.min' => __('validation.min.stringField', ['min' => 8])
        ];
    }
}
