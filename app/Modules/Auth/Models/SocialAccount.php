<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

/**
 * Class SocialAccount
 * @package Modules\Auth\Entities
 * @property int $user_id
 * @property int $provider_id
 * @property string $provider
 * @property string $token
 * @property User $user
 */
class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'token'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
