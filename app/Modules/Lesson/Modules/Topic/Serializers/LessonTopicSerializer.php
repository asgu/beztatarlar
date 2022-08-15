<?php


namespace Modules\Lesson\Modules\Topic\Serializers;


use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Neti\Laravel\Files\Serializers\FileSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class LessonTopicSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            LessonTopic::class => [
                'id',
                'lessonId' => 'lesson_id',
                'title',
                'video' => function (LessonTopic $topic) {
                    if ($topic->video_url) {
                        return [
                            'title' => $topic->video_title,
                            'url' => $topic->video_url,
                        ];
                    }
                    return null;
                },
                "textContent" => function (LessonTopic $topic) {
                    if ($topic->content_text) {
                        return [
                            'title' => $topic->content_title,
                            'body'  => $topic->content_text
                        ];
                    }
                    return null;
                },
                'isPassed' => function (LessonTopic $topic) {
                    return $topic->isPassed();
                },
                'audio' => function (LessonTopic $topic) {
                    if ($topic->audio) {
                        return [
                            'title' => $topic->audio_title,
                            'audio' => FileSerializer::serialize($topic->audio),
                            'description' => $topic->audio_description,
                        ];
                    }
                    return null;
                },
            ]
        ];
    }
}
