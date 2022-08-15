<?php


namespace Modules\Parish\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Parish\Models\Parish;

class ParishRepository
{
    /**
     * @param Parish $parish
     */
    public function save(Parish $parish): void
    {
        $parish->save();
        $parish->refresh();
    }

    /**
     * @param $id
     * @return Parish|null
     */
    public function findById($id): ?Parish
    {
        return Parish::find($id);
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Parish::query();
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
