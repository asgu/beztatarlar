<?php


namespace Modules\User\Modules\Profile\Helpers;


class GenderHelper
{
    public const GENDER_MALE   = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_UNKNOWN = 'unknown';

    /**
     * @return string[]
     */
    public static function genders(): array
    {
        return [
            self::GENDER_MALE   => 'Муж.',
            self::GENDER_FEMALE => 'Жен.',
            self::GENDER_UNKNOWN => 'Не задано',
        ];
    }

    /**
     * @return string[]
     */
    public static function gendersFilter(): array
    {
        return [
            null                => 'Выберите пол',
            self::GENDER_MALE   => 'Муж.',
            self::GENDER_FEMALE => 'Жен.',
        ];
    }

    /**
     * @param string $gender
     * @return string
     */
    public static function genderLabel(string $gender): string
    {
        return self::genders()[$gender] ?? $gender;
    }
}
