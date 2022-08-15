<?php

namespace Modules\Email\Models;

use Modules\Email\Dto\MessageDto;
use Postmark\PostmarkClient;

class ExternalClient extends PostmarkClient implements ExternalClientInterface
{

    public function send(MessageDto $message)
    {
        return $this->sendEmail(
            $message->from,
            $message->to,
            $message->subject,
            $message->htmlBody,
            $message->textBody,
            $message->tag,
            $message->trackOpens,
            $message->replyTo,
            $message->cc,
            $message->bcc,
            $message->headers,
            $message->attachments,
            $message->trackLinks,
            $message->metadata,
            $message->messageStream,
        );
    }
}
