<?php


namespace Modules\Auth\Validation\RulesValidation;

use Illuminate\Validation\Rule;
use Modules\Auth\Services\SocialMedia\Handlers\AppleHandler;
use Modules\Auth\Services\SocialMedia\Handlers\FaceBookHandler;
use Modules\Auth\Services\SocialMedia\Handlers\GoogleHandler;
use Modules\Auth\Services\SocialMedia\Handlers\OKHandler;
use Modules\Auth\Services\SocialMedia\Handlers\VKHandler;
use Netibackend\Laravel\Validation\DataValidator;

class ProviderValidation extends DataValidator
{
    public function rules(): array
    {
        return [
            'provider' => [
                'required',
                Rule::in([
                    GoogleHandler::PROVIDER_NAME,
                    AppleHandler::PROVIDER_NAME,
                    FaceBookHandler::PROVIDER_NAME,
                    VKHandler::PROVIDER_NAME,
                    OKHandler::PROVIDER_NAME
                ])
            ]
        ];
    }
}
