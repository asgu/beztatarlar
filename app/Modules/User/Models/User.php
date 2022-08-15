<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\SocialAccount;
use Modules\Mukhtasibat\Models\Mukhtasibat;
use Modules\Parish\Models\Parish;
use Modules\Position\Models\Position;
use Modules\User\Modules\Profile\Models\UserProfile;
use Modules\UserProgress\Modules\Lesson\Models\LessonUserProgress;
use Modules\UserProgress\Modules\Topic\Models\TopicUserProgress;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Database\Eloquent\BaseAuthenticatable;

/**
 * Class User
 * @package Modules\User\Models
 * @property int $id
 * @property string $created_at
 * @property string $status
 * @property string $password
 * @property string $remember_token
 * @property string $email
 * @property string $timezone
 * @property string $push_notifications
 * @property string $activate_token
 * @property int $mukhtasibat_id
 * @property int $position_id
 * @property int $parish_id
 * @property string $role
 * @property UserProfile $profile
 * @property File|null $photo
 * @property Position|null $position
 * @property Mukhtasibat|null $mukhtasibat
 * @property Parish|null $parish
 * @property Collection $social
 * @property Collection $lessonProgress
 */
class User extends BaseAuthenticatable
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_NEED_CONFIRM = 'need_confirm';

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STUDENT = 'student';

    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'status',
        'email',
        'password',
        'timezone',
        'push_notifications',
        'photo_uuid',
        'mukhtasibat_id',
        'position_id',
        'parish_id',
    ];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value): void
    {
        if (empty($value)) {
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function mukhtasibat(): BelongsTo
    {
        return $this->belongsTo(Mukhtasibat::class, 'mukhtasibat_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function parish(): BelongsTo
    {
        return $this->belongsTo(Parish::class, 'parish_id', 'id');
    }

    /**
     * @return HasOneThrough
     */
    public function photo(): HasOneThrough
    {
        return $this->hasOneThrough(
            File::class,
            UserProfile::class,
            'user_id',
            'uuid',
            'id',
            'photo_uuid'
        );
    }

    /**
     * @return HasMany
     */
    public function social(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return HasMany
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonUserProgress::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function topicProgress(): HasMany
    {
        return $this->hasMany(TopicUserProgress::class, 'user_id', 'id');
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function needConfirm(): bool
    {
        return $this->status == self::STATUS_NEED_CONFIRM;
    }
}
