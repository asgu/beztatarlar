<?php

namespace Modules\Email\Services;

use Illuminate\Support\Facades\App;
use Modules\Email\Dto\MessageDto;
use Modules\Email\Services\External\ExternalMailService;
use Modules\User\Modules\Emails\Models\UserEmail;

class EmailService
{
    private SmtpEmailService $externalMailService;

    public function __construct(SmtpEmailService $externalMailService)
    {
        $this->externalMailService = $externalMailService;
    }

    public function sendRegistrationMail(UserEmail $userEmail): void
    {
        $link = $this->getFrontendUrl('confirm-email?hash=' . $userEmail->hash);

        $message = MessageDto::populateByArray([
            'to' => $userEmail->email,
            'subject' => __('user.registration.confirm.title'),
            'textBody' => __('user.registration.confirm.body', ['link' => $link])
        ]);

        $this->externalMailService->send($message);
    }

    /**
     * @param UserEmail $userEmail
     */
    public function sendPasswordResetMail(UserEmail $userEmail): void
    {
        $link = $this->getFrontendUrl('new-password?hash=' . $userEmail->hash);
        $message = MessageDto::populateByArray([
            'to' => $userEmail->email,
            'subject' => __('user.resetPassword.email.title'),
            'textBody' => __('user.resetPassword.email.body', ['link' => $link])
        ]);

        $this->externalMailService->send($message);
    }

    /**
     * @param UserEmail $userEmail
     */
    public function sendChangeEmailMail(UserEmail $userEmail): void
    {
        $link = $this->getFrontendUrl('change-email?hash=' . $userEmail->hash);

        $message = MessageDto::populateByArray([
            'to' => $userEmail->email,
            'subject' => __('user.changeEmail.email.title'),
            'textBody' => __('user.changeEmail.email.body', ['link' => $link]),
        ]);

        $this->externalMailService->send($message);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getFrontendUrl(string $url): string
    {
        return config('app.frontend_url') . '/' . App::getLocale() . '/' . $url;
    }
}
