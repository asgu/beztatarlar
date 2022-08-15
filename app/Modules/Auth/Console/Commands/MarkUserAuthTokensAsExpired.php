<?php


namespace Modules\Auth\Console\Commands;


use Illuminate\Console\Command;
use Modules\Auth\Services\UserAuthTokenService;

class MarkUserAuthTokensAsExpired extends Command
{
    protected $description = 'Mark expired user auth tokens';
    protected $signature = 'user-auth-tokens:mark-expired';

    protected UserAuthTokenService $userAuthTokenService;

    public function __construct(UserAuthTokenService $userAuthTokenService)
    {
        $this->userAuthTokenService = $userAuthTokenService;
        parent::__construct();
    }

    public function handle(): void
    {
        $this->userAuthTokenService->markAsExpired();
    }
}
