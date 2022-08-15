<?php


namespace Modules\Mukhtasibat\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Mukhtasibat\Models\Mukhtasibat;

class MukhtasibatRepository
{
    /**
     * @param Mukhtasibat $mukhtasibat
     */
    public function save(Mukhtasibat $mukhtasibat): void
    {
        $mukhtasibat->save();
        $mukhtasibat->refresh();
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Mukhtasibat::query();
    }

    /**
     * @param $id
     * @return Mukhtasibat|null
     */
    public function findById($id): ?Mukhtasibat
    {
        return Mukhtasibat::find($id);
    }

    /**
     * @return Builder
     */
    public function findAllActiveQuery(): Builder
    {
        return $this->getQuery()
            ->where('status', ActivityStatusFacade::STATUS_ACTIVE);
    }
}
