<?php


namespace Modules\Email\Services;


use Illuminate\Support\Facades\Mail;
use Modules\Email\Dto\MessageDto;
use Modules\Email\Models\HuzursiteNotification;

class SmtpEmailService
{
    public function send(MessageDto $message)
    {
        Mail::to($message->to)->send(new HuzursiteNotification($message));
    }
}
