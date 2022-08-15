<?php


namespace Modules\Auth\Factories;


use App\Helpers\DateHelper;
use Exception;
use Modules\Auth\Dto\AuthUserDto;
use Modules\Auth\Models\UserAuthToken;
use Modules\User\Models\User;

class UserAuthTokenFactory
{
    /**
     * @param User $user
     * @param AuthUserDto $dto
     *
     * @return UserAuthToken
     * @throws Exception
     */
    public function createByUser(User $user, AuthUserDto $dto): UserAuthToken
    {
        $token = new UserAuthToken();

        $token->user_id = $user->id;
        $token->access_token = null;
        $token->push_token = null;
        $token->status = UserAuthToken::STATUS_ACTIVE;
        $token->created_at = DateHelper::now();
        $token->user_host = $dto->userHost;
        $token->user_agent = $dto->userAgent;
        $token->user_ip = $dto->userIp;
        $token->type = $dto->deviceType;
        $token->device_id = $dto->deviceId;

        return $token;
    }
}
