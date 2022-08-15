<?php


namespace Modules\User\Modules\Emails\Validation\RulesValidation;


use Netibackend\Laravel\Validation\DataValidator;
use Netibackend\Laravel\Validation\Rule;

class UserEmailValidation extends DataValidator
{
    public function validate(array $data): void
    {
        $this->getFactory()
            ->runValidation($data, $this->rules($data), $this->messages(), $this->attributes());
    }

    public function rules(array $data = []): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($data['user_id'] ?? null)
            ],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.requiredField', ['field' => __('columns.users.email')]),
            'user_id.required' => __('validation.requiredField', ['field' => __('columns.users.user_id')]),
        ];
    }
}
