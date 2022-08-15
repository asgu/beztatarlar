<?php

namespace Modules\Lesson\Models;

use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class LessonIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'lessons_intl';

    protected $fillable = [
        'title'
    ];
}
