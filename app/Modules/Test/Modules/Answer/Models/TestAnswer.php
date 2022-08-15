<?php

namespace Modules\Test\Modules\Answer\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Modules\Translation\Models\HasTranslatableIntlTrait;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class TestAnswer
 * @package Modules\Test\Modules\Answer\Models
 * @property int $id
 * @property bool $is_correct
 * @property int $answer
 * @property int $question_id
 * @property TestQuestion $question
 */
class TestAnswer extends BaseEloquent
{
    use HasTranslatableIntlTrait;

    public const CORRECT   = 1;
    public const INCORRECT = 0;

    public $timestamps = false;

    protected $fillable = [
        'is_correct',
        'answer',
        'question_id',
    ];

    public function getTranslatableFields(): array
    {
        return ['answer'];
    }

    public function getIntlModelClass(): string
    {
        return TestAnswerIntl::class;
    }

    public function setIsCorrectAttribute(bool $isCorrect): void
    {
        $this->attributes['is_correct'] = $isCorrect ? 1 : 0;
    }

    public function getIsCorrectAttribute(): bool
    {
        return $this->attributes['is_correct'] ? true : false;
    }

    /**
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(TestQuestion::class, 'question_id', 'id');
    }
}
