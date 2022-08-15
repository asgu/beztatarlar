<?php


namespace Modules\Test\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Test\Models\Test;

class TestRepository
{
    /**
     * @param Test $test
     */
    public function save(Test $test): void
    {
        $test->save();
        $test->refresh();
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Test::query();
    }

    /**
     * @param $id
     * @param array|null $links
     * @return Model|Test|null
     */
    public function findById($id, ?array $links = null): ?Test
    {
        $query = Test::query()->where('id', $id);

        if ($links) {
            $query->with($links);
        }

        return $query->first();
    }

}
