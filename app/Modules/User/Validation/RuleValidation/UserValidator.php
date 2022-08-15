<?php

namespace Modules\User\Validation\RuleValidation;

use Illuminate\Database\Query\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class UserValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'position_id' => [
                'nullable',
                'integer',
                Rule::exists('positions', 'id')
                    ->where(function (Builder $query) {
                        $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
            ],
            'mukhtasibat_id' => [
                'nullable',
                'integer',
                Rule::exists('mukhtasibats', 'id')
                    ->where(function (Builder $query) {
                        $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
            ],
            'parish_id' => [
                'nullable',
                'integer',
                Rule::exists('parishes', 'id')
                    ->where(function (Builder $query) {
                        $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
            ],
        ];
    }
}
