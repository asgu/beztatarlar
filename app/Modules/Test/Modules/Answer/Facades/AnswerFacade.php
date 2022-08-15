<?php


namespace Modules\Test\Modules\Answer\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Test\Modules\Answer\Models\TestAnswer;
use Modules\Test\Modules\Answer\Services\TestAnswerService;

/**
 * Class AnswerFacade
 * @package Modules\Test\Modules\Answer\Facades
 * @method static bool canDelete(TestAnswer $answer)
 */
class AnswerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TestAnswerService::class;
    }
}
