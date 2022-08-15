<?php


namespace Modules\Test\Modules\TestQuestionLink\Validation\RulesValidation;


use Modules\Test\Modules\TestQuestionLink\Validation\Rules\QuestionPriorityRule;
use Netibackend\Laravel\Validation\ModelValidator;

class TestQuestionLinkValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'test_id' => 'required|integer',
            'question_id' => 'required|integer',
            'priority' => [
                'required',
                'integer',
                new QuestionPriorityRule($this->getModel()->id, $this->getModel()->test_id)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'test_id.required' => __('validation.requiredField', ['field' => __('columns.testQuestion.test_id')]),
            'question_id.required' => __('validation.requiredField', ['field' => __('columns.testQuestion.question_id')]),
            'priority.required' => __('validation.requiredField', ['field' => __('columns.testQuestion.priority')]),
            'priority.integer' => __('validation.integerField', ['field' => __('columns.testQuestion.priority')])
        ];
    }
}
