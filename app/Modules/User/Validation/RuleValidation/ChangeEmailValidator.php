<?php


namespace Modules\User\Validation\RuleValidation;


use App\Helpers\DateHelper;
use Illuminate\Database\Query\Builder;
use Modules\User\Modules\Emails\Models\UserEmail;
use Netibackend\Laravel\Validation\DataValidator;
use Netibackend\Laravel\Validation\Rule;

class ChangeEmailValidator extends DataValidator
{
    private $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

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
                Rule::unique('users', 'email'),
                Rule::unique('user_emails', 'email')
                    ->where(function(Builder $query) {
                        $query
                            ->where('type', UserEmail::TYPE_CHANGE_EMAIL)
                            ->where(function ($subquery) {
                                $subquery
                                    ->where('expired_at', '>=', DateHelper::now())
                                    ->orWhere('status', '<>', UserEmail::STATUS_CONFIRM);
                            });
                    })->ignore($this->userId, 'user_id'),
            ],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('user.errors.emailExists'),
            'email.required' => __('validation.requiredField', ['field' => __('columns.users.email')]),
            'password.required' => __('validation.requiredField', ['field' => __('columns.users.password')]),
        ];
    }
}
