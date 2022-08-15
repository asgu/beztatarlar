<?php

namespace Modules\Test\Modules\Question\Models;

use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class TestQuestionIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'test_questions_intl';

    protected $fillable = [
        'question'
    ];
}
