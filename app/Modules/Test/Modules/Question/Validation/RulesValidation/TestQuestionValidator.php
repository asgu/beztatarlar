<?php


namespace Modules\Test\Modules\Question\Validation\RulesValidation;


use Netibackend\Laravel\Validation\ModelValidator;

class TestQuestionValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'question' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => __('validation.requiredField', ['field' => __('columns.testQuestions.question')]),
        ];
    }
}
