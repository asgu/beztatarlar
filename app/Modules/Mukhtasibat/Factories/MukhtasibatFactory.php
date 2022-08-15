<?php


namespace Modules\Mukhtasibat\Factories;


use JetBrains\PhpStorm\Pure;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Mukhtasibat\Models\Mukhtasibat;

class MukhtasibatFactory
{
    /**
     * @return Mukhtasibat
     */
    #[Pure]
    public function create(): Mukhtasibat
    {
        $mukhtasibat = new Mukhtasibat();
        $mukhtasibat->status = ActivityStatusFacade::STATUS_ACTIVE;

        return $mukhtasibat;
    }
}
