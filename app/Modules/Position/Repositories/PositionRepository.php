<?php


namespace Modules\Position\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Position\Models\Position;

class PositionRepository
{
    /**
     * @param Position $position
     */
    public function save(Position $position): void
    {
        $position->save();
        $position->refresh();
    }

    /**
     * @param $id
     * @return Position|null
     */
    public function findById($id): ?Position
    {
        return Position::find($id);
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Position::query();
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
