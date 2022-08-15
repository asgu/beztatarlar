<?php


namespace Modules\Teacher\Serializers;


use Modules\Teacher\Models\Teacher;
use Neti\Laravel\Files\Models\File;
use Neti\Laravel\Files\Serializers\FileSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class TeacherSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            Teacher::class => [
                'id',
                'name',
                'description',
                'photo'
            ],
            File::class => FileSerializer::class
        ];
    }
}
