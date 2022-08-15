<?php


namespace Modules\Auth\Repositories;

use App\Helpers\DateHelper;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\UserAuthToken;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserAuthTokenRepository
{
    /**
     * @param UserAuthToken $userAuthToken
     */
    public function save(UserAuthToken $userAuthToken): void
    {
        $userAuthToken->save();
    }

    /**
     * @param $accessToken
     *
     * @return UserAuthToken|Model
     * @throws Exception
     */
    public function getActiveToken($accessToken): ?UserAuthToken
    {
        return UserAuthToken::query()->where([
            'access_token' => $accessToken,
            'status' => UserAuthToken::STATUS_ACTIVE
        ])->first();
    }

    /**
     * @param User $user
     * @return Builder
     */
    public function getActiveTokensByUser(User $user): Builder
    {
        return UserAuthToken::query()->where([
            'user_id' => $user->id,
            'status' => UserAuthToken::STATUS_ACTIVE
        ]);
    }

    /**
     * @return Builder
     * @throws Exception
     */
    public function getExpiredTokens(): Builder
    {
        return UserAuthToken::query()->where([
            'status' => UserAuthToken::STATUS_ACTIVE
        ])->whereDate('created_at', '<', DateHelper::getModifyDate(DateHelper::now(), '-24 hour'));
    }

    /**
     * @param User $user
     */
    public function deleteAllByUser(User $user): void
    {
        UserAuthToken::query()
            ->where('user_id', $user->id)
            ->delete();
    }
}
