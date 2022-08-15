<?php

namespace Modules\User\Exceptions;

use Netibackend\Laravel\Exceptions\UnSuccessException;

class BlockUserException extends UnSuccessException
{
    public function __construct($message, array $errors = null)
    {
        parent::__construct($message, $errors, 403);
    }
}
