<?php


namespace Modules\Email\Dto;


use Netibackend\Laravel\DTO\AbstractDto;

class MessageDto extends AbstractDto
{
    /** @var mixed|string */
    public $from;

    /** @var mixed|string */
    public $to;

    /** @var mixed|string */
    public $subject;

    /** @var null mixed|string */
    public $htmlBody = null;

    /** @var null mixed|string */
    public $textBody = null;

    /** @var null mixed|string */
    public $tag = null;

    /** @var null mixed|string */
    public $trackOpens = null;

    /** @var null mixed|string */
    public $replyTo = null;

    /** @var null mixed|string */
    public $cc = null;

    /** @var null mixed|string */
    public $bcc = null;

    /** @var null mixed|string */
    public $headers = null;

    /** @var null mixed|string */
    public $attachments = null;

    /** @var null mixed|string */
    public $trackLinks = null;

    /** @var null mixed|string */
    public $metadata = null;

    /** @var null mixed|string */
    public $messageStream = null;

}
