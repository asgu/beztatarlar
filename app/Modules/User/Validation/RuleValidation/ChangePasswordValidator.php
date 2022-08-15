<?php


namespace Modules\User\Validation\RuleValidation;


use Netibackend\Laravel\Validation\DataValidator;

class ChangePasswordValidator extends DataValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'currentPassword' => [
                'required',
                'string'
            ],
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'different:currentPassword'
            ],
            'repeatNewPassword' => [
                'required',
                'string',
                'same:newPassword'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'repeatNewPassword.same' => __('user.changePassword.errors.notSamePasswords'),
            'newPassword.different' => __('user.changePassword.errors.passwordInUse'),
            'newPassword.min' => __('validation.min.stringField', ['min' => 8])
        ];
    }
}
