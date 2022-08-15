<?php


namespace Modules\Auth\Validation\RulesValidation;

use Illuminate\Validation\Rule;
use Modules\Auth\Models\UserAuthToken;
use Netibackend\Laravel\Validation\DataValidator;

class CreateTokenValidation extends DataValidator
{
    public function rules(): array
    {
        return [
            'deviceId'   => ['string', 'max:255'],
            'deviceType' => ['required', Rule::in([UserAuthToken::TYPE_WEB, UserAuthToken::TYPE_IOS, UserAuthToken::TYPE_ANDROID])],
            'userHost'   => ['required'],
            'userAgent'  => ['required'],
            'userIp'     => ['required'],
        ];
    }
}
