<?php

namespace Modules\Language\Services;

use Modules\Language\Models\AppLanguage;
use Modules\Language\Repositories\LanguageRepository;
use Netibackend\Laravel\Exceptions\NotFoundException;

class LanguageService
{
    private LanguageRepository $repository;

    public function __construct(
        LanguageRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function getAppLanguagesList(): array
    {
        return $this->repository->getAppLanguagesList()->all();
    }

    public function getAppLanguagesLabels(): array
    {
        $result = [];
        foreach ($this->getAppLanguagesList() as $item) {
            $result[$item->code] = $item->name;
        }

        return $result;
    }

    public function existAppLanguagesExistByCode($code): bool
    {
        return $this->repository->existAppLanguagesByCode($code);
    }

    public function getTryByCode($code): AppLanguage
    {
        $lang = $this->repository->getByCode($code);

        if (!$lang) {
            throw new NotFoundException(__('app.language.notSupported'));
        }

        return $lang;
    }

    public function getByCode($code): ?AppLanguage
    {
        return $this->repository->getByCode($code);
    }

    public function languageExistByCode($code): bool
    {
        return $this->repository->existAppLanguagesByCode($code);
    }
}
