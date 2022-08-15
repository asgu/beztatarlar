<?php

namespace Modules\Position\Models;

use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class Position
 * @package Modules\Position\Models
 * @property int $id
 * @property string $title
 * @property string $status
 */
class Position extends BaseEloquent
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
        return PositionIntl::class;
    }
}
