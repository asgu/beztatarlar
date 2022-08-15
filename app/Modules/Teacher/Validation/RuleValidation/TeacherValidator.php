<?php


namespace Modules\Teacher\Validation\RuleValidation;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\File\Validation\Rules\FileModelExtensionRule;
use Modules\Teacher\Models\Teacher;
use Modules\Teacher\Validation\Rules\ColumnValueCountRule;
use Neti\Laravel\Files\Helpers\FileTypesHelper;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class TeacherValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:250',
            'status' => [
                'required',
                Rule::in([ActivityStatusFacade::STATUS_ACTIVE, ActivityStatusFacade::STATUS_INACTIVE]),
                new ColumnValueCountRule(Teacher::class, ActivityStatusFacade::STATUS_ACTIVE, 3)
            ],
            'photo' => [
                'required',
                new FileModelExtensionRule(FileTypesHelper::ALLOW_IMAGES_EXTENSIONS)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.requiredField', ['field' => __('columns.teachers.name')]),
            'description.required' => __('validation.requiredField', ['field' => __('columns.teachers.description')]),
            'status.required' => __('validation.requiredField', ['field' => __('columns.teachers.status')]),
            'photo.required' => __('validation.requiredField', ['field' => __('columns.teachers.photo')]),
        ];
    }
}
