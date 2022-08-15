<?php


namespace Modules\Language\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Modules\Language\Models\AppLanguage;

class LanguageRepository
{
    public function getAppLanguagesList(): Collection
    {
        return AppLanguage::query()->orderBy('sort_index')->get();
    }

    public function existAppLanguagesByCode(?string $code): bool
    {
        return AppLanguage::where('code', $code)->exists();
    }

    public function getByCode(?string $code): ?AppLanguage
    {
        return AppLanguage::where('code', $code)->first();
    }
}
