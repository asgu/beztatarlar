<?php

namespace Modules\Certificate\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\File\Database\Eloquent\PropertyTraits\FileTrait;
use Modules\User\Models\User;
use Neti\Laravel\Files\Models\File;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Certificate
 * @package Modules\Certificate\Models
 * @property int $id
 * @property string $file_uuid
 * @property string $created_at
 * @property int $user_id
 * @property File $file
 * @property User createdByUser
 */
class Certificate extends BaseEloquent
{
    use FileTrait;

    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'file_uuid');
    }

    /**
     * @return HasOne
     */
    public function createdByUser(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
