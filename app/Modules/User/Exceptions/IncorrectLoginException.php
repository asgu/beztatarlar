<?php


namespace Modules\User\Exceptions;


use App\Exceptions\DataValidationException;

class IncorrectLoginException extends DataValidationException
{
    protected $loginArrt = 'email';
    protected $errorMessage = 'user.auth.errors.wrongEmail';

    public function __construct(?array $messages = null)
    {
        if (!$messages) {
            $messages = [
                $this->loginArrt => __($this->errorMessage)
            ];
        }
        parent::__construct($messages);
    }
}
