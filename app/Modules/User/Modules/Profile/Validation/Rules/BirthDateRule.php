<?php


namespace Modules\User\Modules\Profile\Validation\Rules;


use DateTime;
use Illuminate\Contracts\Validation\Rule;

class BirthDateRule implements Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        $now = new DateTime();
        try {
            $date = new DateTime($value);
        } catch (\Exception $exception) {
            return false;
        }
        if ($date > $now) {
            return false;
        }
        $old = new DateTime('1900-01-01');
        return $date > $old;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return __('validation.incorrectDate');
    }
}
