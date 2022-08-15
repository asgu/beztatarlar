<?php

namespace Modules\Position\Models;

use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class PositionIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'positions_intl';

    protected $fillable = [
        'title'
    ];
}
