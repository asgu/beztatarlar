<?php


namespace Modules\User\Modules\Profile\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Modules\Profile\Models\UserProfile;

class UserProfileRepository
{
    /**
     * @param UserProfile $profile
     */
    public function save(UserProfile $profile): void
    {
        $profile->save();
        $profile->refresh();
    }

    /**
     * @param $value
     * @return Collection
     */
    public function findBySurname($value): Collection
    {
        return UserProfile::query()->where('surname', 'like', "%$value%")->get();
    }

    /**
     * @param $value
     * @return Collection
     */
    public function findByName($value): Collection
    {
        return UserProfile::query()->where('name', 'like', "%$value%")->get();
    }

    /**
     * @param $value
     * @return Collection
     */
    public function findByPatronymic($value): Collection
    {
        return UserProfile::query()->where('patronymic', 'like', "%$value%")->get();
    }

    /**
     * @param UserProfile $profile
     */
    public function delete(UserProfile $profile): void
    {
        $profile->delete();
    }

    /**
     * @return Builder
     */
    public function certificatesQuery(): Builder
    {
        return UserProfile::query()
            ->with(['certificate'])
            ->whereNotNull('certificate_uuid');
    }

    /**
     * @param $id
     * @return Builder|Model|null
     */
    public function findById($id): Model|UserProfile|null
    {
        return UserProfile::query()
            ->where(['id' => $id])
            ->first();
    }

}
