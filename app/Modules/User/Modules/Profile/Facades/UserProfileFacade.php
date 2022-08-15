<?php


namespace Modules\User\Modules\Profile\Facades;


use Modules\User\Modules\Profile\Models\UserProfile;

class UserProfileFacade
{
    /**
     * @param UserProfile|null $profile
     * @param bool $withPatronymic
     * @return string
     */
    public static function fullName(?UserProfile $profile, bool $withPatronymic = true): string
    {
        if (!$profile) {
            return '';
        }

        $name = [
            $profile->surname,
            $profile->name,
        ];

        if ($withPatronymic) {
            $name[] = $profile->patronymic;
        }

        return implode(' ', $name);
    }
}
