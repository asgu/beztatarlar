<?php


namespace Modules\Test\Modules\Answer\Validation\RulesValidation;


use Netibackend\Laravel\Validation\ModelValidator;

class TestAnswerValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required|integer',
            'answer' => 'required|string|max:200',
            'is_correct' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'question_id.required' => __('validation.requiredField', ['field' => __('columns.testAnswers.question_id')]),
            'answer.required' => __('validation.requiredField', ['field' => __('columns.testAnswers.answer')]),
            'is_correct.required' => __('validation.requiredField', ['field' => __('columns.testAnswers.is_correct')]),
        ];
    }
}
