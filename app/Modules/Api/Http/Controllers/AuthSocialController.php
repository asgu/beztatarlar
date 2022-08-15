<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Auth\Dto\AuthUserDto;
use Modules\Auth\Serializers\UserAuthTokenSerializer;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Services\SocialMedia\Handlers\AppleHandler;
use Modules\Auth\Services\SocialMedia\Handlers\FaceBookHandler;
use Modules\Auth\Services\SocialMedia\Handlers\GoogleHandler;
use Modules\Auth\Services\SocialMedia\Handlers\OKHandler;
use Modules\Auth\Services\SocialMedia\Handlers\VKHandler;
use Modules\Auth\Services\SocialMedia\SocialService;
use Netibackend\Laravel\Api\Serializers\JSendResponse;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class AuthSocialController extends BaseApiController
{
    /**
     * @var AuthService
     */
    private AuthService $authService;
    /**
     * @var SocialService
     */
    private SocialService $socialService;

    public function __construct(AuthService $authService, SocialService $socialService)
    {
        $this->authService = $authService;
        $this->socialService = $socialService;
    }

    /**
     * @return array
     */
    public function list(): array
    {
        return $this->socialService->list();
    }

    /**
     * @param Request $request
     * @param string $provider
     * @return RedirectResponse
     */
    public function callback(Request $request, string $provider): RedirectResponse
    {
        $code = $request->get('code');
        $redirectUrl = $this->socialService->prepareRedirectUrl($code, $provider);

        return Redirect::away($redirectUrl)->send();
    }

    /**
     * @param Request $request
     * @param string $provider
     * @return array
     * @throws ValidationException
     * @throws DataValidationException
     * @throws Exception
     */
    public function login(Request $request, string $provider): array
    {
        $user = $this->socialService->getSocialUser($provider);
        $user = $this->socialService->findOrCreateUserBySocialData($provider, $user);
        $authDto = AuthUserDto::populateByRequest($request);
        $token = $this->authService->createToken($user, $authDto);

        return UserAuthTokenSerializer::serialize($token);
    }
}
