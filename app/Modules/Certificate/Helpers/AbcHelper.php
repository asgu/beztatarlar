<?php


namespace Modules\Certificate\Helpers;


class AbcHelper
{
    private static array $map = [
        'а' => 66,
        'б' => 68,
        'в' => 60,
        'г' => 44,
        'д' => 75,
        'е' => 68,
        'ё' => 68,
        'ж' => 90,
        'з' => 56,
        'и' => 60,
        'й' => 60,
        'к' => 54,
        'л' => 70,
        'м' => 76,
        'н' => 60,
        'о' => 68,
        'п' => 58,
        'р' => 64,
        'с' => 64,
        'т' => 60,
        'у' => 66,
        'ф' => 102,
        'х' => 68,
        'ц' => 66,
        'ч' => 60,
        'ш' => 90,
        'щ' => 98,
        'ъ' => 78,
        'ы' => 80,
        'ь' => 60,
        'э' => 64,
        'ю' => 88,
        'я' => 66,
    ];

    /**
     * @param string $letter
     * @param int $default
     * @return int
     */
    public static function getLetterWidth(string $letter, int $default = 0): int
    {
        return self::$map[$letter] ?? $default;
    }
}
