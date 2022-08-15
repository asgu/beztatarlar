<?php


namespace Modules\User\Validation\RuleValidation;



use Illuminate\Database\Query\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\DataValidator;
use Netibackend\Laravel\Validation\Rule;

class RegistrationValidator extends DataValidator
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
            'password' => ['required', 'string', 'min:8'],
            'repeatPassword' => ['required', 'string', 'same:password'],
            'agreement' => ['required', 'boolean', 'accepted'],
            'positionId' => [
                'nullable',
                'integer',
                Rule::exists('positions', 'id')
                    ->where(function (Builder $query) {
                        $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
            ],
            'mukhtasibatId' => [
                'nullable',
                'integer',
                Rule::exists('mukhtasibats', 'id')
                    ->where(function (Builder $query) {
                        $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
            ],
            'parishId' => [
                'nullable',
                'integer',
                Rule::exists('parishes', 'id')
                    ->where(function (Builder $query) {
                        $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
            ],
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
