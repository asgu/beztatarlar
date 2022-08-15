<?php

namespace Modules\Parish\Models;

use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Parish
 * @package Modules\Parish\Models
 * @property int $id
 * @property string $status
 * @property string $title
 */
class Parish extends BaseEloquent
{
    use HasTranslatableIntlTrait;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'status'
    ];

    public function getTranslatableFields(): array
    {
        return ['title'];
    }

    public function getIntlModelClass(): string
    {
        return ParishIntl::class;
    }
}
