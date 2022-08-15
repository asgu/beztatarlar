<?php

namespace Modules\Auth\Services\SocialMedia;

use App\Repositories\Traits\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Auth\Dto\SocialUserDTO;
use Modules\Auth\Dto\UserFromSocialDTO;
use Modules\Auth\Repositories\SocialAccountRepository;
use Modules\Auth\Services\SocialMedia\Handlers\AppleHandler;
use Modules\Auth\Services\SocialMedia\Handlers\BaseHandler;
use Modules\Auth\Services\SocialMedia\Handlers\FaceBookHandler;
use Modules\Auth\Services\SocialMedia\Handlers\GoogleHandler;
use Modules\Auth\Services\SocialMedia\Handlers\OKHandler;
use Modules\Auth\Services\SocialMedia\Handlers\VKHandler;
use Modules\Auth\Validation\RulesValidation\ProviderValidation;
use Modules\User\Models\User;
use Modules\User\Services\ApiUserService;
use Modules\User\Services\UserService;
use Netibackend\Laravel\Exceptions\UnSuccessException;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialService
{
    use Transaction;

    public const PASSWORD_LENGTH = 6;

    private SocialAccountRepository $socialAccountRepository;
    private ApiUserService $userRegistrationService;
    private ProviderValidation $providerValidation;
    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct
    (
        SocialAccountRepository $socialAccountRepository,
        ApiUserService $userRegistrationService,
        UserService $userService,
        ProviderValidation $providerValidation
    )
    {
        $this->socialAccountRepository = $socialAccountRepository;
        $this->userRegistrationService = $userRegistrationService;
        $this->providerValidation = $providerValidation;
        $this->userService = $userService;
    }

    /**
     * @return array[]
     */
    public function list(): array
    {
        return [
//            [
//                'name' => GoogleHandler::PROVIDER_NAME,
//                'link' => route('auth.social', 'google'),
//            ],
//            [
//                'name' => FaceBookHandler::PROVIDER_NAME,
//                'link' => route('auth.social', 'facebook'),
//            ],
            [
                'name' => VKHandler::PROVIDER_NAME,
                'link' => route('auth.social', 'vkontakte'),
            ],
            [
                'name' => OKHandler::PROVIDER_NAME,
                'link' => route('auth.social', 'odnoklassniki'),
            ]
        ];
    }

    /**
     * @param string $provider
     * @param array $socialUser
     * @return User
     * @throws DataValidationException
     * @throws ValidationException
     * @throws Exception
     */
    public function findOrCreateUserBySocialData(string $provider, array $socialUser): User
    {
        $this->providerValidation->validate(['provider' => $provider]);
        $handler = $this->getHandlerByProvider($provider);

        $socialUserDTO = $handler->getUserData($socialUser);

        $socialUser = $this->socialAccountRepository->findByParams($socialUserDTO);

        if (empty($socialUser)) {
            return $this->createUser($socialUserDTO);
        }

        return $socialUser->user;
    }

    /**
     * @param string $provider
     * @return BaseHandler
     */
    private function getHandlerByProvider(string $provider): BaseHandler
    {
        $handlerMap = [
            'google'        => GoogleHandler::class,
            'apple'         => AppleHandler::class,
            'facebook'      => FaceBookHandler::class,
            'vkontakte'     => VKHandler::class,
            'odnoklassniki' => OKHandler::class
        ];

        if (!isset($handlerMap[$provider])) {
            Log::error('Неизвестный провайдер ' . $provider);
            throw new UnSuccessException(__('socialMedia.errors.invalidProvider'));
        }

        return app($handlerMap[$provider]);
    }

    /**
     * @param SocialUserDTO $socialUserDTO
     * @return User
     * @throws Exception
     */
    private function createUser(SocialUserDTO $socialUserDTO): User
    {
        if (empty($socialUserDTO->email)) {
            throw new UnSuccessException(__('socialMedia.errors.emailDoesNotExist'));
        }

        $user = $this->userService->getByEmail($socialUserDTO->email);

        if (!empty($user)) {
            if ($user->needConfirm()) {
                $this->userRegistrationService->confirmUser($user);
            }
            $this->addSocialAccountToUser($user, $socialUserDTO);
            return $user;
        }

        $password = Str::random(self::PASSWORD_LENGTH);
        $email = $socialUserDTO->email;

        if(!$socialUserDTO->email) {
            $email = Str::random(40) . '@social.ru';
        }

        /** @var UserFromSocialDTO $userFromSocialDTO */
        $userFromSocialDTO = UserFromSocialDTO::populateByArray([
            'name'              => $socialUserDTO->name,
            'email'             => $email,
            'password'          => $password,
        ]);

        $this->beginTransaction();
        try {
            $user = $this->userRegistrationService->registrateFromSocial($userFromSocialDTO);
            $this->addSocialAccountToUser($user, $socialUserDTO);
            $this->commitTransaction();
        } catch (Exception $e) {
            Log::error('Ошибка при создании пользователя через соц сети ' . $e->getMessage());
            $this->rollbackTransaction();
            throw new UnSuccessException(__('socialMedia.errors.cantCreateUser'));
        }

        return $user;
    }

    /**
     * @param User $user
     * @param SocialUserDTO $socialUserDTO
     */
    public function addSocialAccountToUser(User $user, SocialUserDTO $socialUserDTO): void
    {
        $user->social()->updateOrCreate([
            'provider'    => $socialUserDTO->provider,
            'provider_id' => $socialUserDTO->provider_id
        ], [
            'token' => $socialUserDTO->token
        ]);
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws UnSuccessException
     * @throws DataValidationException
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->providerValidation->validate(['provider' => $provider]);
        $handler = $this->getHandlerByProvider($provider);

        try {
            return $handler->redirect();
        } catch (Exception $exception) {
            Log::error(__METHOD__ . ' Ошибка отображения формы ' . $provider . ' ' . $exception->getMessage());
            throw new UnSuccessException(__('socialMedia.errors.cantShowForm', ['provider' => $provider]));
        }
    }

    /**
     * @param string $provider
     * @return array
     * @throws UnSuccessException
     * @throws DataValidationException
     */
    public function getSocialUser(string $provider): array
    {
        $this->providerValidation->validate(['provider' => $provider]);
        $handler = $this->getHandlerByProvider($provider);

        try {
            return $handler->getSocialUser();
        } catch (Exception $exception) {
            Log::error(__METHOD__ . ' Ошибка получения данных пользователя ' . $provider . ' ' . $exception->getMessage());
            throw new UnSuccessException(__('socialMedia.errors.cantGetUserInfo', ['provider' => $provider]));
        }
    }

    /**
     * @param mixed $code
     * @param string $provider
     * @return string
     */
    public function prepareRedirectUrl(mixed $code, string $provider): string
    {
        $redirectUrl = config('app.frontend_url') . '/register';
        $redirectUrl .= '?code=' . $code;
        $redirectUrl .= '&provider=' . $provider;
        return $redirectUrl;
    }
}
