<?php


namespace Modules\Position\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Position\Models\Position;

class PositionFactory
{
    /**
     * @return Position
     */
    public function create(): Position
    {
        $position = new Position();
        $position->status = ActivityStatusFacade::STATUS_ACTIVE;

        return $position;
    }
}
