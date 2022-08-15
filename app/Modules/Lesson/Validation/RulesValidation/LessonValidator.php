<?php


namespace Modules\Lesson\Validation\RulesValidation;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class LessonValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'priority' => [
                'required',
                'integer',
                'max:100000000',
                Rule::unique('lessons', 'priority')
                    ->where(function ($query) {
                        return $query->where('status', ActivityStatusFacade::STATUS_ACTIVE);
                    })
                    ->ignore($this->getModel()->id)
            ],
            'status' => [
                'required',
                Rule::in([ActivityStatusFacade::STATUS_ACTIVE, ActivityStatusFacade::STATUS_INACTIVE])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('validation.requiredField', ['field' => __('columns.lessons.title')]),
            'status.required' => __('validation.requiredField', ['field' => __('columns.lessons.status')]),
            'priority.required' => __('validation.requiredField', ['field' => __('columns.lessons.priority')]),
            'priority.unique' => __('validation.unique', ['field' => __('columns.lessons.priority')]),
            'priority.integer' => __('validation.integerField', ['field' => __('columns.lessons.priority')]),
            'priority.max' => __('validation.maxField', ['field' => __('columns.lessons.priority')]),
        ];
    }
}
