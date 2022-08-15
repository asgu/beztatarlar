<?php


namespace Modules\User\Validation\RuleValidation;


use Netibackend\Laravel\Validation\DataValidator;
use Netibackend\Laravel\Validation\Rule;

class PreRegistrationValidator extends DataValidator
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'password' => ['required', 'string', 'min:8'],
            'repeatPassword' => ['required', 'string', 'same:password'],
            'agreement' => ['required', 'boolean', 'accepted']
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('user.errors.emailExists'),
            'email.required' => __('validation.requiredField', ['field' => __('columns.users.email')]),
            'password.required' => __('validation.requiredField', ['field' => __('columns.users.password')]),
            'repeatPassword.required' => __('validation.requiredField', ['field' => __('columns.users.repeatPassword')]),
            'agreement.required' => __('validation.requiredField', ['field' => __('columns.users.agreement')]),
            'repeatPassword.same' => __('validation.same', ['field' => __('columns.users.password')]),
            'agreement.accepted' => __('validation.agreement'),
            'password.min' => __('validation.min.stringField', ['min' => 8])
        ];
    }
}
