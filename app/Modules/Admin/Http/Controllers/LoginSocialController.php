<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Auth\Services\SocialMedia\SocialService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Symfony\Component\HttpFoundation\RedirectResponse;


class LoginSocialController extends Controller
{
    private SocialService $socialService;

    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws DataValidationException
     */
    public function redirect(string $provider): RedirectResponse
    {
        return $this->socialService->redirect($provider);
    }
}
