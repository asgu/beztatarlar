<?php


namespace Modules\User\Modules\Profile\Validation\Rules;


use Illuminate\Contracts\Validation\Rule;
use Modules\User\Modules\Profile\Helpers\PhoneHelper;

class PhoneRule implements Rule
{
    public ?string $pattern = '/^([7|8])(\d{10})$/';

    /**
     * @var null|mixed
     */
    protected mixed $valueRaw = null;

    /**
     * @param string|mixed $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->valueRaw = $value;
        return preg_match($this->pattern, PhoneHelper::cleanRawPhone($value));
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return __('netiLibrary.validation.phone');
    }
}
