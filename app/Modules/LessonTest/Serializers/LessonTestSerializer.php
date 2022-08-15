<?php


namespace Modules\LessonTest\Serializers;


use Modules\LessonTest\Models\LessonTest;
use Modules\Test\Models\Test;
use Modules\Test\Serializers\TestPreviewSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class LessonTestSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            LessonTest::class => [
                'id',
                'isPassed' => function (LessonTest $test) {
                    return $test->isPassed();
                },
                'isBlocked' => function (LessonTest $test) {
                    return $test->isBlocked();
                },
                'content' => 'test'
            ],
            Test::class => TestPreviewSerializer::class
        ];
    }
}
