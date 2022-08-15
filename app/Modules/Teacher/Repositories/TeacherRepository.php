<?php


namespace Modules\Teacher\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Teacher\Models\Teacher;

class TeacherRepository
{
    /**
     * @param Teacher $teacher
     */
    public function save(Teacher $teacher): void
    {
        $teacher->save();
        $teacher->refresh();
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Teacher::query();
    }

    /**
     * @param $id
     * @return Model|Teacher|null
     */
    public function findById($id): ?Teacher
    {
        return Teacher::query()->with(['photo'])->find($id);
    }
}
