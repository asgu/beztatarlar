<?php

namespace Modules\Test\Modules\TestQuestionLink\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Test\Models\Test;
use Modules\Test\Modules\Question\Models\TestQuestion;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Class TestQuestionLink
 * @package Modules\Test\Modules\TestQuestionLink\Models
 * @property int $id
 * @property int $test_id
 * @property int $question_id
 * @property int $priority
 * @property TestQuestion $question
 * @property Test $test
 */
class TestQuestionLink extends BaseEloquent
{
    public $timestamps = false;

    protected $fillable = [
        'test_id',
        'question_id',
        'priority',
    ];

    /**
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(TestQuestion::class, 'question_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id', 'id');
    }
}
