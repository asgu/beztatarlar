<?php


namespace Modules\ActivityStatus\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\ActivityStatus\Services\ActivityStatusService;

/**
 * Class ActivityStatusFacade
 * @package Modules\ActivityStatus\Facades
 * @method static array statuses()
 * @method static string statusLabel(string $status)
 */
class ActivityStatusFacade extends Facade
{
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ActivityStatusService::class;
    }
}
