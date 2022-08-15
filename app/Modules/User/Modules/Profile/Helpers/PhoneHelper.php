<?php


namespace Modules\User\Modules\Profile\Helpers;


class PhoneHelper
{
    /**
     * @param mixed $phone
     * @return string
     */
    public static function cleanRawPhone(mixed $phone): string
    {
        $phone = trim($phone);
        $phone = str_replace(['+', '-', ' ', '(', ')', '/', '\\'], '', $phone);
        return $phone;
    }
}
