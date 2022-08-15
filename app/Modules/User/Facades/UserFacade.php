<?php


namespace Modules\User\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\User\Models\User;
use Modules\User\Modules\Profile\Facades\UserProfileFacade;
use Modules\User\Modules\Profile\Helpers\GenderHelper;
use Modules\User\Services\UserService;

/**
 * Class UserFacade
 * @package Modules\User\Facades
 * @method static array roles()
 * @method static string roleLabel(string $role)
 */
class UserFacade extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return UserService::class;
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function name(User $user): ?string
    {
        return self::getRelationAttribute($user, 'profile', 'name');
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function surname(User $user): ?string
    {
        return self::getRelationAttribute($user, 'profile', 'surname');
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function patronymic(User $user): ?string
    {
        return self::getRelationAttribute($user, 'profile', 'patronymic');
    }

    /**
     * @param User $user
     * @param bool $withPatronymic
     * @return string
     */
    public static function fullName(User $user, bool $withPatronymic = true): string
    {
        return UserProfileFacade::fullName($user->profile, $withPatronymic);
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function birthday(User $user): ?string
    {
        return self::getRelationAttribute($user, 'profile', 'birthday');
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function genderLabel(User $user): ?string
    {
        return GenderHelper::genderLabel(
            self::getRelationAttribute($user, 'profile', 'gender')
        );
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function phone(User $user): ?string
    {
        return self::getRelationAttribute($user, 'profile', 'phone');
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function position(User $user): ?string
    {
        return self::getRelationAttribute($user, 'position', 'title');
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function mukhtasibat(User $user): ?string
    {
        return self::getRelationAttribute($user, 'mukhtasibat', 'title');
    }

    /**
     * @param User $user
     * @return string|null
     */
    public static function parish(User $user): ?string
    {
        return self::getRelationAttribute($user, 'parish', 'title');
    }

    /**
     * @param User $user
     * @param string $relation
     * @param string $attr
     * @return string|null
     */
    protected static function getRelationAttribute(User $user, string $relation, string $attr): ?string
    {
        if ($user->$relation) {
            return $user->$relation->$attr;
        }
        return null;
    }

}
