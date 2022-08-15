<?php


namespace Modules\Position\Validation\RuleValidation;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class PositionValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:50',
                Rule::unique('positions', 'title')->ignore($this->getModel()->id)
            ],
            'status' => [
                'required',
                Rule::in([ActivityStatusFacade::STATUS_ACTIVE, ActivityStatusFacade::STATUS_INACTIVE])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('validation.requiredField', ['field' => __('columns.positions.title')]),
            'status.required' => __('validation.requiredField', ['field' => __('columns.positions.status')]),
        ];
    }
}
