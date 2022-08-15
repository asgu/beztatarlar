<?php

namespace Modules\Email\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Dto\MessageDto;

class HuzursiteNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var MessageDto
     */
    protected MessageDto $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MessageDto $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Email::emails.simple-email')
            ->with(['text' => $this->message->textBody]);
    }
}
