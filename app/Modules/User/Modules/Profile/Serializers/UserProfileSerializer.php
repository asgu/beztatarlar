<?php


namespace Modules\User\Modules\Profile\Serializers;


use Modules\User\Modules\Profile\Facades\UserProfileFacade;
use Modules\User\Modules\Profile\Models\UserProfile;
use Neti\Laravel\Files\Models\File;
use Neti\Laravel\Files\Serializers\FileSerializer;
use Netibackend\Laravel\Serializers\AbstractProperties;

class UserProfileSerializer extends AbstractProperties
{

    /**
     * @inheritDoc
     */
    public function getProperties(): array
    {
        return [
            UserProfile::class => [
                'name',
                'surname',
                'patronymic',
                'fullName' => function (UserProfile $profile) {
                    return UserProfileFacade::fullName($profile);
                },
                'birthday',
                'gender',
                'phone',
                'photo',
                'certificate'
            ],
            File::class => FileSerializer::class
        ];
    }
}
