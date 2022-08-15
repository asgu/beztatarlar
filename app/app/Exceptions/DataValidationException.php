<?php


namespace App\Exceptions;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class DataValidationException extends \Netibackend\Laravel\Validation\Exceptions\DataValidationException
{
    public function __construct(?array $messages = null)
    {
        parent::__construct(tap(ValidatorFacade::make([], []), function ($validator) use ($messages) {
            foreach ($messages as $key => $value) {
                foreach (Arr::wrap($value) as $message) {
                    $validator->errors()->add($key, $message);
                }
            }
        }));
    }
}
