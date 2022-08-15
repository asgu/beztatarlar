<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;

/**
 * Class RedisServiceFacade
 * @package App\Services
 *
 * @method static void deleteByPattern($pattern)
 */
class RedisServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RedisService::class;
    }
}
