<?php

namespace Modules\Email\Models;

use Modules\Email\Dto\MessageDto;

interface ExternalClientInterface
{
    public function send(MessageDto $message);
}
