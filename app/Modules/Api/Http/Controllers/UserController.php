<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Modules\User\Dto\ChangeEmailDto;
use Modules\User\Dto\ChangePasswordDto;
use Modules\User\Dto\UserDto;
use Modules\User\Exceptions\IncorrectPasswordException;
use Modules\User\Modules\Emails\Services\UserEmailService;
use Modules\User\Modules\Profile\Serializers\UserProfileSerializer;
use Modules\User\Serializers\UserSerializer;
use Modules\User\Services\ApiUserService;
use Netibackend\Laravel\Api\Serializers\JSendResponse;

class UserController extends BaseApiController
{
    /**
     * @var ApiUserService
     */
    private ApiUserService $userService;
    /**
     * @var UserEmailService
     */
    private UserEmailService $userEmailService;

    public function __construct(ApiUserService $userService, UserEmailService $userEmailService)
    {
        $this->userService = $userService;
        $this->userEmailService = $userEmailService;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthenticationException
     * @throws Exception
     */
    public function update(Request $request): array
    {
        $dto = UserDto::populateByArray($request->all());
        $user = $this->getCurrentUser();
        $this->userService->update($user, $dto);

        $user->refresh();
        return UserSerializer::serialize($user);
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws AuthenticationException
     * @throws IncorrectPasswordException
     * @throws Exception
     */
    public function changeEmail(Request $request): JSendResponse
    {
        $dto = ChangeEmailDto::populateByArray($request->all());
        $this->userService->ensureCanChangeEmail($this->getCurrentUser(), $dto);

        $this->userEmailService->sendChangeEmailConfirm($this->getCurrentUser(), $dto);

        return JSendResponse::send(__('user.changeEmail.email.sent'));
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws Exception
     */
    public function confirmChangeEmail(Request $request): JSendResponse
    {
        $this->userService->confirmChangeEmail($request->get('hash'));
        return JSendResponse::send(__('app.success'));
    }

    /**
     * @param Request $request
     * @return JSendResponse
     * @throws AuthenticationException
     * @throws IncorrectPasswordException
     */
    public function changePassword(Request $request): JSendResponse
    {
        $dto = ChangePasswordDto::populateByArray($request->all());
        $this->userService->changePassword($this->getCurrentUser(), $dto);

        return JSendResponse::send(__('user.changePassword.success'));
    }

    /**
     * @return array
     * @throws AuthenticationException
     */
    public function profile(): array
    {
        return UserSerializer::serialize($this->getCurrentUser());
    }
}
