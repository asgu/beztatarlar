<?php

namespace Modules\Mukhtasibat\Models;

use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Mukhtasibat
 * @package Modules\Mukhtasibat\Models
 * @property int $id
 * @property string $title
 * @property string $status
 */
class Mukhtasibat extends BaseEloquent
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
        return MukhtasibatIntl::class;
    }
}
