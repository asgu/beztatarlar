<?php


namespace Modules\Test\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Test\Models\Test;

class TestFactory
{
    /**
     * @return Test
     */
    public function create(): Test
    {
        $test = new Test();
        $test->status = ActivityStatusFacade::STATUS_ACTIVE;

        return $test;
    }
}
