<?php


namespace Modules\UserProgress\Modules\Lesson\Validation\RulesValidation;


use Netibackend\Laravel\Validation\ModelValidator;

class LessonUserProgressValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'lesson_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'   => __('validation.required', ['field' => __('columns.lesson_user_progress.user_id')]),
            'lesson_id.required' => __('validation.required', ['field' => __('columns.lesson_user_progress.lesson_id')]),
        ];
    }
}
