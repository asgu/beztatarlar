<?php

namespace Modules\Teacher\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\File\Database\Eloquent\PropertyTraits\PhotoTrait;
use Modules\Translation\Models\HasTranslatableIntlTrait;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Teacher
 * @package Modules\Teacher\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $photo_uuid
 * @property string $status
 * @property File|null $photo
 */
class Teacher extends BaseEloquent
{
    use PhotoTrait;
    use HasTranslatableIntlTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function getTranslatableFields(): array
    {
        return ['name', 'description'];
    }

    public function getIntlModelClass(): string
    {
        return TeacherIntl::class;
    }

    /**
     * @return HasOne
     */
    public function photo(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'photo_uuid');
    }
}
