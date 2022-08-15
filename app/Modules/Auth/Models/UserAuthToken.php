<?php


namespace Modules\Auth\Models;


use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\User\Models\User;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class UserAuthToken
 * @package Modules\Auth\Models
 *
 * @property integer $id
 * @property string $status
 * @property string $created_at
 * @property string $last_used_at
 * @property integer $user_id
 * @property string $type
 * @property string $access_token
 * @property string $push_token
 * @property string $device_id
 * @property string $user_host
 * @property string $user_agent
 * @property string $user_ip
 *
 * @property User $user
 */
class UserAuthToken extends BaseEloquent
{
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_EXPIRED  = 'expired';
    public const STATUS_INACTIVE = 'inactive';

    public const TYPE_ANDROID = 'android';
    public const TYPE_IOS     = 'ios';
    public const TYPE_WEB     = 'web';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
