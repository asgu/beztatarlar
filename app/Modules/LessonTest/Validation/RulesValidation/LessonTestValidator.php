<?php


namespace Modules\LessonTest\Validation\RulesValidation;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class LessonTestValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'lesson_id' => 'required|integer',
            'test_id' => 'required|integer',
            'status' => [
                'required',
                Rule::in([ActivityStatusFacade::STATUS_ACTIVE, ActivityStatusFacade::STATUS_INACTIVE])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'lesson_id.required' => __('validation.requiredField', ['field' => __('columns.lessonTests.lesson_id')]),
            'test_id.required' => __('validation.requiredField', ['field' => __('columns.lessonTests.test_id')]),
            'status.required' => __('validation.requiredField', ['field' => __('columns.lessonTests.status')]),
        ];
    }
}
