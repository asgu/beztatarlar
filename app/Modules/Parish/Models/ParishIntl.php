<?php

namespace Modules\Parish\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

class ParishIntl extends BaseEloquent
{
    public $timestamps = false;

    protected $table = 'parishes_intl';

    protected $fillable = [
        'title'
    ];
}
