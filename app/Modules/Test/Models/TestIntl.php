<?php

namespace Modules\Test\Models;

use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class TestIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'tests_intl';

    protected $fillable = [
        'title',
        'description'
    ];
}
