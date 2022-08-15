<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    public function deleteByPattern($pattern): void
    {
        $keys = Redis::connection()->command('keys', [$pattern]);

        $prefix = config('database.redis.options.prefix');
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $key = str_replace($prefix, '', $key);
                Redis::connection()->command('del', [$key]);
            }
        }
    }
}
