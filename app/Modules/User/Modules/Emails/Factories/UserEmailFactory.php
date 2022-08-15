<?php


namespace Modules\User\Modules\Emails\Factories;


use Exception;
use Modules\User\Modules\Emails\Models\UserEmail;
use Netibackend\Laravel\Helpers\DateHelper;

class UserEmailFactory
{
    /**
     * @param string|null $type
     * @return UserEmail
     * @throws Exception
     */
    public function create(?string $type = null): UserEmail
    {
        $userEmail = new UserEmail();

        $userEmail->created_at = DateHelper::now('UTC');
        $userEmail->status = UserEmail::STATUS_SENT;
        $userEmail->type = $type ?? UserEmail::TYPE_MESSAGE;

        return $userEmail;
    }
}
