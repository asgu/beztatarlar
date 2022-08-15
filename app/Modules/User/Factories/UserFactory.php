<?php


namespace Modules\User\Factories;


use App\Helpers\DateHelper;
use Exception;
use Modules\User\Models\User;

class UserFactory
{
    /**
     * @param string $role
     * @return User
     * @throws Exception
     */
    public function create(string $role): User
    {
        $user = new User();
        $user->created_at = DateHelper::now();
        $user->status = User::STATUS_NEED_CONFIRM;
        $user->role = $role;

        return $user;
    }
}
