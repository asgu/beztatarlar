<?php


namespace Modules\UserProgress\Modules\Topic\Validation\RulesValidation;


use Netibackend\Laravel\Validation\ModelValidator;

class TopicUserProgressValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'topic_id'  => 'required',
            'user_id'   => 'required',
            'lesson_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'topic_id.required'  => __('validation.required', ['field' => __('columns.topic_user_progress.topic_id')]),
            'user_id.required'   => __('validation.required', ['field' => __('columns.topic_user_progress.user_id')]),
            'lesson_id.required' => __('validation.required', ['field' => __('columns.topic_user_progress.lesson_id')]),
        ];
    }
}
