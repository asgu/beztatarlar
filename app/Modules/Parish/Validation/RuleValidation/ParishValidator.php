<?php


namespace Modules\Parish\Validation\RuleValidation;


use Illuminate\Validation\Rule;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\ModelValidator;

class ParishValidator extends ModelValidator
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
                'max:100',
                Rule::unique('parishes', 'title')->ignore($this->getModel()->id)
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
            'title.required' => __('validation.requiredField', ['field' => __('columns.parishes.title')]),
            'status.required' => __('validation.requiredField', ['field' => __('columns.parishes.status')]),
        ];
    }
}
