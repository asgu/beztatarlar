<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\DTO\SocialUserDTO;
use Modules\Auth\Models\SocialAccount;

class SocialAccountRepository
{
    public $model;

    public function __construct()
    {
        $this->model = app(self::getModelName());
    }

    /**
     * @return string
     */
    public static function getModelName(): string
    {
        return SocialAccount::class;
    }

    /**
     * @param SocialUserDTO $userDTO
     * @return SocialAccount|null
     */
    public function findByParams(SocialUserDTO $userDTO): ?SocialAccount
    {
        return $this->model
            ->where('provider', $userDTO->provider)
            ->where('provider_id', $userDTO->provider_id)
            ->first();
    }
}
