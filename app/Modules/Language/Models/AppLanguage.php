<?php


namespace Modules\Language\Models;


use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class AppLanguage
 * @package Modules\Language\Models
 *
 * @property string $code
 * @property string $name
 * @property integer $sort_index
 */
class AppLanguage extends BaseEloquent
{
    public $timestamps = false;
    public $incrementing = false;

    public $table = 'app_languages';

    protected $primaryKey = 'code';
    protected $keyType = 'string';
}
