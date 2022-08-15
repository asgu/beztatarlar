<?php

namespace Modules\Auth\Services\SocialMedia\Handlers;

use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Dto\SocialUserDTO;
use Modules\Auth\Validation\RulesValidation\SocialUserDataValidation;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class BaseHandler
{
    private SocialUserDataValidation $socialUserDataValidation;

    public function __construct(SocialUserDataValidation $socialUserDataValidation)
    {
        $this->socialUserDataValidation = $socialUserDataValidation;
    }

    /**
     * @param array $socialUser
     * @return SocialUserDTO
     * @throws ValidationException
     */
    public function getUserData(array $socialUser): SocialUserDTO
    {
        $this->socialUserDataValidation->validate($socialUser);
        $userData = [
            'provider'    => static::PROVIDER_NAME,
            'provider_id' => $socialUser['id'],
            'name'        => $socialUser['name'] ?? 'user',
            'token'       => $socialUser['token'],
            'email'       => $socialUser['email']
        ];
        /** @var SocialUserDTO $socialUserDTO */
        $socialUserDTO = SocialUserDTO::populateByArray($userData);
        return $socialUserDTO;
    }

    public function getSocialUser(): array
    {
        return (array) Socialite::driver(static::PROVIDER_NAME)->stateless()->user() ?? [];
    }

    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver(static::PROVIDER_NAME)->stateless()->redirect();
    }
}
