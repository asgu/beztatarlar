<?php


namespace Modules\Auth\Validation\RulesValidation;


use Netibackend\Laravel\Validation\DataValidator;

class SocialUserDataValidation extends DataValidator
{
    public function rules(): array
    {
        return [
            'id'    => 'required',
            'token' => 'required',
            'email' => 'required|email',
        ];
    }
}
