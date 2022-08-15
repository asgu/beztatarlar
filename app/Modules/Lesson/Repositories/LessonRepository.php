<?php


namespace Modules\Lesson\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;
use Modules\Lesson\Models\Lesson;

class LessonRepository
{
    /**
     * @param Lesson $lesson
     */
    public function save(Lesson $lesson): void
    {
        $lesson->save();
        $lesson->refresh();
    }

    /**
     * @param $id
     * @return Lesson
     */
    public function findById($id): Lesson
    {
        return Lesson::find($id);
    }

    /**
     * @return Builder
     */
    protected function getActiveQuery(): Builder
    {
        return Lesson::query()
            ->where('lessons.status', ActivityStatusFacade::STATUS_ACTIVE);
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Lesson::query();
    }

    /**
     * @return Collection
     */
    public function findWithoutTest(): Collection
    {
        return $this->getActiveQuery()
            ->select('lessons.*')
            ->leftJoin('lesson_tests', function (JoinClause $join) {
                $join->on('lessons.id', '=', 'lesson_tests.lesson_id')
                    ->where('lesson_tests.status', '=', ActivityStatusFacade::STATUS_ACTIVE);
            })
            ->where('lesson_tests.id', null)
            ->orderBy('lessons.priority')
            ->get();
    }

    /**
     * @param $title
     * @return Collection
     */
    public function findByTitle($title): Collection
    {
        return Lesson::query()
            ->where('title', 'LIKE', "%$title%")
            ->orderBy('priority')
            ->get();
    }

    /**
     * @return Builder
     */
    public function findAllActiveQuery(): Builder
    {
        return $this->getActiveQuery()
            ->with(['activeTopics', 'test'])
            ->orderBy('priority');
    }

    /**
     * @param Lesson $lesson
     * @return Collection
     */
    public function findAllActivePrevious(Lesson $lesson): Collection
    {
        return $this->getActiveQuery()
            ->where('priority', '<', $lesson->priority)
            ->get();
    }

    /**
     * @param int|null $priority
     * @return Builder|Model|Lesson|null
     */
    public function findNextActiveByPriority(?int $priority): ?Lesson
    {
        $query = $this->getActiveQuery()
            ->orderBy('priority');

        if (!is_null($priority)) {
            $query->where('priority', '>', $priority);
        }

        return $query->first();
    }

    /**
     * @param Lesson $lesson
     * @return Builder|Model|Lesson|null
     */
    public function findActivePrevious(Lesson $lesson): ?Lesson
    {
        return $this->getActiveQuery()
            ->where('priority', '<', $lesson->priority)
            ->orderBy('priority', 'desc')
            ->first();
    }
}
