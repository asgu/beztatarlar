<?php


namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\User;

class UserRepository
{
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $user->save();
        $user->refresh();
    }

    /**
     * @param mixed $id
     *
     * @return User|null
     */
    public function getById(mixed $id): ?User
    {
        return User::find($id);
    }

    /**
     * Get all Model
     *
     * @return Builder
     */
    public function getAdminQuery(): Builder
    {
        return User::query()->with(['profile'])->select('users.*');
    }

    /**
     * @param $role
     * @return Collection
     */
    public function findByRole($role): Collection
    {
        return User::query()->where('role', $role)->get();
    }

    /**
     * @param $email
     * @return Collection
     */
    public function findAllByEmail($email): Collection
    {
        return User::query()->where('email', 'LIKE',"%$email%")->get();
    }

    /**
     * @param $email
     * @return Builder|Model|User|null
     */
    public function findByEmail($email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
