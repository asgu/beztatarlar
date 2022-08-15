<?php


namespace Modules\User\Validation\RuleValidation;


use Modules\User\Models\User;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class SocialMediaRegistrationValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'password' => ['required', 'string'],
            'role' => [Rule::in(User::ROLE_STUDENT)],
            'status' => [Rule::in(User::STATUS_ACTIVE)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('user.errors.emailExists'),
            'email.required' => __('validation.requiredField', ['field' => __('columns.users.email')]),
            'password.required' => __('validation.requiredField', ['field' => __('columns.users.password')]),
            'repeatPassword.same' => __('validation.same', ['field' => __('columns.users.password')]),
            'agreement.accepted' => __('validation.agreement'),
        ];
    }
}
