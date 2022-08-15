<?php

namespace Modules\Test\Modules\Question\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\TestQuestionLink\Models\TestQuestionLink;
use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class TestQuestion
 * @package Modules\Test\Modules\Question\Models
 * @property int $id
 * @property string $question
 * @property TestQuestionLink|null $link
 * @property Collection $answers
 */
class TestQuestion extends BaseEloquent
{
    use HasTranslatableIntlTrait;

    public $timestamps = false;

    protected $fillable = [
        'question',
    ];

    public function getTranslatableFields(): array
    {
        return ['question'];
    }

    public function getIntlModelClass(): string
    {
        return TestQuestionIntl::class;
    }

    /**
     * @return HasOne
     */
    public function link(): HasOne
    {
        return $this->hasOne(TestQuestionLink::class, 'question_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class, 'question_id', 'id');
    }
}
