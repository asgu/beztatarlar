<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Http\Request;
use Modules\Auth\Dto\AuthUserDto;
use Modules\Auth\Serializers\UserAuthTokenSerializer;
use Modules\Auth\Services\AuthService;
use Modules\User\Dto\PasswordResetDto;
use Modules\User\Exceptions\IncorrectLoginException;
use Modules\User\Modules\Emails\Services\UserEmailService;
use Modules\User\Services\UserService;
use Netibackend\Laravel\Api\Serializers\JSendResponse;

class PasswordResetController extends BaseApiController
{

    /**
     * @var UserService
     */
    private UserService $userService;
    /**
     * @var UserEmailService
     */
    private UserEmailService $userEmailService;
    /**
     * @var AuthService
     */
    private AuthService $authService;

    public function __construct(UserService $userService, UserEmailService $userEmailService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->userEmailService = $userEmailService;
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws IncorrectLoginException
     * @throws Exception
     */
    public function sendToken(Request $request): JSendResponse
    {
        $user = $this->userService->tryGetByEmail($request->post('email'));
        $this->userEmailService->sendPasswordResetToken($user);

        return JSendResponse::send(__('user.resetPassword.email.sent'));
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws Exception
     */
    public function reset(Request $request): JSendResponse
    {
        $email = $this->userEmailService->tryGetPasswordResetEmailByHash($request->get('resetToken'));
        $dto = PasswordResetDto::populateByArray($request->all());
        $this->userService->resetPassword($email->user, $dto);
        $this->userEmailService->confirm($email);
        $this->authService->logoutFromAllDevices($email->user);
        $authDto = AuthUserDto::populateByRequest($request);
        $token = $this->authService->createToken($email->user, $authDto);
        return JSendResponse::send('Пароль успешно изменен', UserAuthTokenSerializer::serialize($token));
    }
}
