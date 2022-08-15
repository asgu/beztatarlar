<?php

namespace Modules\Mukhtasibat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class MukhtasibatIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'mukhtasibats_intl';

    protected $fillable = [
        'title'
    ];
}
