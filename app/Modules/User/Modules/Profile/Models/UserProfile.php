<?php


namespace Modules\User\Modules\Profile\Models;


use App\Helpers\DateHelper;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\File\Database\Eloquent\PropertyTraits\CertificateTrait;
use Modules\File\Database\Eloquent\PropertyTraits\PhotoTrait;
use Modules\User\Models\User;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class UserProfile
 * @package Modules\User\Modules\Profile\Models
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $birthday
 * @property string $gender
 * @property string $phone
 * @property string $photo_uuid
 * @property string $certificate_uuid
 * @property User $user
 * @property File|null $photo
 * @property File|null $certificate
 */
class UserProfile extends BaseEloquent
{
    use PhotoTrait;
    use CertificateTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'birthday',
        'gender',
        'phone',
    ];

    /**
     * @param $value
     * @throws Exception
     */
    public function setBirthdayAttribute($value): void
    {
        if (!$value) {
            $this->attributes['birthday'] = null;
            return;
        }
        $this->attributes['birthday'] = $value;
        $date = date_parse($value);

        if ($date['error_count'] === 0 && $date['year']) {
            $this->attributes['birthday'] = DateHelper::formatDate($value, 'Y-m-d');
        }
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function photo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'photo_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(File::class, 'certificate_uuid', 'uuid');
    }
}
