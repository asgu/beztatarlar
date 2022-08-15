<?php


namespace Modules\Language\Models;


use App\Support\JsonSupport;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\File\Database\Eloquent\PropertyTraits\FileTrait;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class AppLanguageMessage
 * @package Modules\Language\Models
 *
 * @property int $id
 * @property string $code
 * @property string $type
 * @property array $message_values
 * @property string|null $file_uuid
 * @property-read string|null $message_values_json
 *
 * @property File $file
 * @property AppLanguage $appLanguage
 */
class AppLanguageMessage extends BaseEloquent
{
    use FileTrait;

    public const TYPE_BACKEND = 'backend';
    public const TYPE_FRONTEND = 'frontend';
    public const TYPE_FRONTEND_ARGUMENTS = 'frontendAgreements';
    public const TYPE_FRONTEND_PRIVACY_POLICY = 'frontendPrivacyPolicy';

    public $timestamps = false;

    public $table = 'app_language_messages';

    protected $fillable = [
        'code',
        'type',
        'message_values',
        'file_uuid'
    ];

    protected $casts = [
        'message_values' => 'array'
    ];

    public function appLanguage(): HasOne
    {
        return $this->hasOne(AppLanguage::class, 'code', 'code');
    }

    public function getMessageValuesJsonAttribute(): ?string
    {
        return is_array($this->message_values) ? JsonSupport::encode($this->message_values) : null;
    }

    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'file_uuid');
    }
}
