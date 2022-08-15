<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Modules\Auth\Dto\AuthUserDto;
use Modules\Auth\Dto\UserLoginDto;
use Modules\Auth\Serializers\UserAuthTokenSerializer;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Validation\RulesValidation\CreateTokenValidation;
use Modules\User\Dto\RegistrationDto;
use Modules\User\Exceptions\IncorrectLoginException;
use Modules\User\Exceptions\IncorrectPasswordException;
use Modules\User\Modules\Emails\Services\UserEmailService;
use Modules\User\Services\ApiUserService;
use Modules\User\Validation\RuleValidation\PreRegistrationValidator;
use Netibackend\Laravel\Api\Serializers\JSendResponse;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class AuthController extends BaseApiController
{
    /**
     * @var ApiUserService
     */
    private ApiUserService $userService;
    /**
     * @var AuthService
     */
    private AuthService $authService;
    /**
     * @var UserEmailService
     */
    private UserEmailService $userEmailService;

    public function __construct(
        ApiUserService $userService,
        AuthService $authService,
        UserEmailService $userEmailService
    ) {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->userEmailService = $userEmailService;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws DataValidationException
     * @throws Exception
     */
    public function registration(Request $request): array
    {
        $dto = RegistrationDto::populateByArray($request->all());
        $authUserDto = AuthUserDto::populateByRequest($request);

        CreateTokenValidation::validateStatic($authUserDto->toArray());

        $user = $this->userService->registrateUser($dto);
        $token = $this->authService->createToken($user, $authUserDto);

        $this->userEmailService->sendRegistrationConfirm($user);

        return UserAuthTokenSerializer::serialize($token);
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws Exception
     */
    public function preRegistrationValidation(Request $request): JSendResponse
    {
        $dto = RegistrationDto::populateByArray($request->all());
        PreRegistrationValidator::validateStatic($dto->toArray());
        return JSendResponse::send(__('app.success'));
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws Exception
     */
    public function confirmEmail(Request $request): JSendResponse
    {
        $this->userService->confirmRegistrationEmail($request->get('hash'));
        return JSendResponse::send(__('user.email.confirmed'));
    }

    /**
     * @param Request $request
     * @return array
     * @throws IncorrectLoginException
     * @throws IncorrectPasswordException
     * @throws Exception
     */
    public function login(Request $request): array
    {
        $dto = UserLoginDto::populateByRequest($request);
        $user = $this->userService->getByCredentials($dto);
        $token = $this->authService->createToken($user, $dto);

        return UserAuthTokenSerializer::serialize($token);
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws Exception
     */
    public function logout(Request $request): JSendResponse
    {
        $this->authService->logoutByAccessToken($request->bearerToken());
        return JSendResponse::send(__('user.auth.logout.success'));
    }

    /**
     * @throws AuthenticationException
     * @throws Exception
     */
    public function sendRegistrationEmail(): JSendResponse
    {
        $user = $this->getCurrentUser();
        $this->userEmailService->reSendRegistrationConfirm($user);
        return JSendResponse::send(__('user.email.confirmSent'));
    }
}
