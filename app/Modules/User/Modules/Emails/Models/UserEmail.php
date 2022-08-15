<?php


namespace Modules\User\Modules\Emails\Models;


use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;
use Netibackend\Laravel\Helpers\DateHelper;

/**
 * Class UserConfirmRequest
 * @package Modules\UserConfirm\Models
 *
 * @property string $created_at
 * @property string $expired_at
 * @property string $email
 * @property string $hash
 * @property string $status
 * @property string $type
 * @property integer $user_id
 *
 * @property User $user
 */
class UserEmail extends BaseEloquent
{
    public const STATUS_SENT = 'sent';
    public const STATUS_CONFIRM = 'confirm';
    public const STATUS_EXPIRED = 'expired';

    public const TYPE_MESSAGE = 'message';
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_PASSWORD_RESET = 'password_reset';
    public const TYPE_CHANGE_EMAIL = 'change_email';

    public $timestamps = false;

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isExpired(): bool
    {
        if (!$this->expired_at) {
            return false;
        }
        $expired = DateHelper::strtotime($this->expired_at, 'UTC');
        $now = DateHelper::strtotime('now', 'UTC');

        return $expired < $now;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
