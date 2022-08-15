<?php

namespace Modules\Email\Services\External;

use Illuminate\Support\Facades\Log;
use Modules\Email\Dto\MessageDto;
use Modules\Email\Models\ExternalClient;

class ExternalMailService
{
    private string $fromEmail;
    private ExternalClient $client;

    public function __construct()
    {
        $this->fromEmail = config('services.postmark.from_email');

        $authToken = config('services.postmark.token');
        if (empty($authToken)) {
            return;
        }

        $this->client = new ExternalClient($authToken);
    }

    public function send(MessageDto $message): bool
    {
        if (empty($this->client)) {
            return false;
        }

        try {
            $message->from = $this->fromEmail;
            $sendResult = $this->client->send($message);

            if (!empty($sendResult->error_code)) {
                return false;
            }

            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        return false;
    }
}
