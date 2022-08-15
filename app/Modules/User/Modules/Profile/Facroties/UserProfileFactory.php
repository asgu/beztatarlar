<?php


namespace Modules\User\Modules\Profile\Facroties;


use JetBrains\PhpStorm\Pure;
use Modules\User\Modules\Profile\Models\UserProfile;

class UserProfileFactory
{
    /**
     * @return UserProfile
     */
    #[Pure]
    public function create(): UserProfile
    {
        return new UserProfile();
    }
}
