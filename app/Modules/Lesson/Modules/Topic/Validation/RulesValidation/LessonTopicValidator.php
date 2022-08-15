<?php


namespace Modules\Lesson\Modules\Topic\Validation\RulesValidation;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\File\Validation\Rules\FileModelExtensionRule;
use Modules\Lesson\Modules\Topic\Validation\Rules\TopicPriorityRule;
use Neti\Laravel\Files\Helpers\FileTypesHelper;
use Netibackend\Laravel\Validation\ModelValidator;
use Netibackend\Laravel\Validation\Rule;

class LessonTopicValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'lesson_id' => 'required|integer',
            'title' => 'required|string|max:200',
            'video_title' => 'nullable|string|max:200',
            'video_url' => 'required_without_all:content_text,audio|nullable|active_url',
            'content_title' => 'nullable|string|max:200',
            'content_text' => 'required_without_all:video_url,audio|nullable|string',
            'audio_title' => 'nullable|string|max:200',
            'audio_description' => 'nullable|string',
            'audio' => [
                'required_without_all:video_url,content_text',
                'nullable',
                new FileModelExtensionRule(FileTypesHelper::ALLOW_AUDIO_EXTENSIONS)
            ],
            'priority' => [
                'required',
                'integer',
                new TopicPriorityRule(
                    $this->getModel()->id,
                    $this->getModel()->lesson_id,
                    $this->getModel()->status
                )
            ],
            'timer' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('validation.requiredField', ['field' => __('columns.lesson_topics.title')]),
            'video_url.active_url' => __('validation.activeUrlField', ['field' => __('columns.lesson_topics.video_url')]),
            'video_url.required_without_all' => __('validation.required_without_all', ['fields' => implode(', ', [
                __('columns.lesson_topics.video_url'),
                __('columns.lesson_topics.content_text'),
                __('columns.lesson_topics.audio'),
            ])]),
            'content_text.required_without_all' => __('validation.required_without_all', ['fields' => implode(', ', [
                __('columns.lesson_topics.video_url'),
                __('columns.lesson_topics.content_text'),
                __('columns.lesson_topics.audio'),
            ])]),
            'audio.required_without_all' => __('validation.required_without_all', ['fields' => implode(', ', [
                __('columns.lesson_topics.video_url'),
                __('columns.lesson_topics.content_text'),
                __('columns.lesson_topics.audio'),
            ])]),
            'title.max' => __('validation.maxStringField', ['field' => __('columns.lesson_topics.title')]),
            'video_title.max' => __('validation.maxStringField', ['field' => __('columns.lesson_topics.video_title')]),
            'content_title.max' => __('validation.maxStringField', ['field' => __('columns.lesson_topics.content_title')]),
            'audio_title.max' => __('validation.maxStringField', ['field' => __('columns.lesson_topics.audio_title')]),
            'priority.integer' => __('validation.integerField', ['field' => __('columns.lesson_topics.priority')]),
            'priority.required' => __('validation.requiredField', ['field' => __('columns.lesson_topics.priority')]),
        ];
    }
}
