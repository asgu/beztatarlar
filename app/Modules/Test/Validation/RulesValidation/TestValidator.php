<?php


namespace Modules\Test\Validation\RulesValidation;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class TestValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'status' => [
                'required',
                Rule::in([ActivityStatusFacade::STATUS_INACTIVE, ActivityStatusFacade::STATUS_ACTIVE])
            ],
            'timer' => 'nullable|string|max:200'
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => __('validation.requiredField', ['field' => __('columns.tests.status')]),
            'title.required' => __('validation.requiredField', ['field' => __('columns.tests.title')]),
            'timer.max' => __('validation.maxStringField', ['field' => __('columns.tests.timer')])
        ];
    }
}
