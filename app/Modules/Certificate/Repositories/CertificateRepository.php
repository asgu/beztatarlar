<?php


namespace Modules\Certificate\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Certificate\Models\Certificate;
use Modules\User\Models\User;

class CertificateRepository
{
    /**
     * @param Certificate $certificate
     */
    public function save(Certificate $certificate): void
    {
        $certificate->save();
        $certificate->refresh();
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return Certificate::query();
    }

    /**
     * @return Builder|Model|Certificate|null
     */
    public function findLatest(): ?Certificate
    {
        return Certificate::query()->orderByDesc('created_at')->first();
    }

    /**
     * @param $id
     * @return ?Certificate
     */
    public function findById($id): ?Certificate
    {
        return Certificate::find($id);
    }

    /**
     * @param Certificate $certificate
     */
    public function delete(Certificate $certificate): void
    {
        $certificate->delete();
    }

    /**
     * @param User $user
     */
    public function deleteByUser(User $user)
    {
        Certificate::query()
            ->where('user_id', $user->id)
            ->delete();
    }
}
