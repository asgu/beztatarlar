<?php


namespace Modules\User\Modules\Emails\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;
use Modules\User\Modules\Emails\Models\UserEmail;

class UserEmailRepository
{
    public function save(UserEmail $userConfirmRequest)
    {
        $userConfirmRequest->save();
    }

    /**
     * @param string $type
     * @param string|null $hash
     * @return Model|UserEmail|null
     */
    public function getUserEmailByTypeAndHash(string $type, ?string $hash): ?UserEmail
    {
        return UserEmail::query()
            ->where(['hash' => $hash, 'type' => $type])
            ->first();
    }

    /**
     * @param int $user_id
     * @return Builder
     */
    public function getSentConfirmRequestByUserId(int $user_id): Builder
    {
        return UserEmail::query()->where([
            'user_id' => $user_id,
            'status' => UserEmail::STATUS_SENT
        ]);
    }

    /**
     * @param string $status
     * @param array $whereClause
     */
    public function setStatusForAll(string $status, array $whereClause): void
    {
        UserEmail::query()
            ->where($whereClause)
            ->update(['status' => $status]);
    }

    /**
     * @param User $user
     * @param string $type
     */
    public function setExpiredAll(User $user, string $type): void
    {
        UserEmail::query()
            ->where(['email' => $user->email, 'type' => $type])
            ->update(['status' => UserEmail::STATUS_EXPIRED]);
    }

    /**
     * @param User $user
     * @return Builder|Model|UserEmail|null
     */
    public function getRegistrationEmailByUser(User $user): ?UserEmail
    {
        return UserEmail::query()
            ->where('email', $user->email)
            ->where('type', UserEmail::TYPE_REGISTRATION)
            ->where('status', UserEmail::STATUS_SENT)
            ->first();
    }

    /**
     * @param User $user
     */
    public function deleteAllByUser(User $user)
    {
        UserEmail::query()
            ->where('user_id', $user->id)
            ->delete();
    }
}
