<?php

namespace Modules\Teacher\Models;

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
class TeacherIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'teachers_intl';

    protected $fillable = [
        'name',
        'description',
    ];
}
