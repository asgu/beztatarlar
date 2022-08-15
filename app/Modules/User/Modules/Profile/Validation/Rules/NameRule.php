<?php


namespace Modules\User\Modules\Profile\Validation\Rules;


use Illuminate\Contracts\Validation\Rule;

class NameRule implements Rule
{
    public ?string $pattern = '/^[A-zА-я-]+$/u';

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        return preg_match($this->pattern, $value);
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return __('user.profile.errors.incorrectNameValue');
    }
}
