<?php


namespace Modules\Parish\Factories;


use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Parish\Models\Parish;

class ParishFactory
{
    /**
     * @return Parish
     */
    public function create(): Parish
    {
        $parish = new Parish();
        $parish->status = ActivityStatusFacade::STATUS_ACTIVE;

        return $parish;
    }
}
