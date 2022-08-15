<?php

namespace Modules\Test\Modules\Answer\Models;

use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class TestAnswerIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'test_answers_intl';

    protected $fillable = [
        'answer'
    ];
}
