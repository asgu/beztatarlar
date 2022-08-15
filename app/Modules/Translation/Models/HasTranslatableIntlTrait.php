<?php

namespace Modules\Translation\Models;

use App\Services\RedisServiceFacade;
use App\Support\JsonSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use LogicException;
use Modules\Translation\Services\TranslationServiceFacade;
use Netibackend\Laravel\Database\Eloquent\BaseEloquent;

/**
 * Trait HasTranslatableIntlTrait
 * @package Modules\Translation\Models
 */
trait HasTranslatableIntlTrait
{
    /**
     * Current model languages
     */
    private ?string $_currentLanguage = null;

    /**
     * Intl array map cache
     */
    private array $_translateIntl = [];

    /**
     * Changed languages
     */
    private array $_changedLanguages = [];

    public function getCurrentLanguage(): string
    {
        return $this->_currentLanguage ?? App::getLocale();
    }

    public function setCurrentLanguage(?string $lang): void
    {
        $this->_currentLanguage = $lang;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($key)
    {
        if ($this->isTranslatable($key)) {
            $translate = $this->getTranslateIntl($this->getCurrentLanguage());

            if (isset($translate[$key])) {
                return $translate[$key];
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * @inheritDoc
     */
    public function setAttribute($key, $value): void
    {
        if ($this->isTranslatable($key)) {
            $this->_translateIntl[$this->getCurrentLanguage()][$key] = $value;
            $this->_changedLanguages[$this->getCurrentLanguage()] = true;
            return;
        }

        parent::setAttribute($key, $value);
    }

    public function save(array $options = []): bool
    {
        $parent = parent::save($options);

        if ($parent) {
            $this->saveTranslations();
        }

        return $parent;
    }

    public function saveTranslations(): void
    {
        foreach ($this->_changedLanguages as $changedLanguage => $value) {
            if (isset($this->_translateIntl[$changedLanguage]) && $value) {
                $model = $this->getIntlModel($changedLanguage);

                $model->fill($this->_translateIntl[$changedLanguage]);
                $model->model_id = $this->getAttribute('id');
                $model->lang_code = $changedLanguage;

                $model->save();

                $pattern = $this->generationCacheKey($model->lang_code);
                RedisServiceFacade::deleteByPattern($pattern);
            }
        }

        $this->_changedLanguages = [];
    }

    public function isTranslatable($key): bool
    {
        return in_array($key, $this->getTranslatableFields(), true) && $this->isNotDefaultLanguage();
    }

    public function getTranslatableFields(): array
    {
        return [];
    }

    /**
     * @return string
     * @throws LogicException
     */
    public function getIntlModelClass(): string
    {
        throw new LogicException('Intl model class not found.');
    }

    private function isNotDefaultLanguage(): bool
    {
        return !TranslationServiceFacade::isDefaultLocale($this->getCurrentLanguage());
    }

    private function getTranslateIntl($lang): array
    {
        // check local cache
        if (isset($this->_translateIntl[$lang])) {
            return $this->_translateIntl[$lang];
        }

        // check redis or apcu
        $cachedValues = $this->getCachedTranslateIntl($lang);
        if ($cachedValues !== null) {
            return $cachedValues;
        }

        // get database
        $className = $this->getIntlModelClass();
        /** @var Builder $builder */
        $builder = $className::query();

        $model = $builder
            ->select($this->getTranslatableFields())
            ->where('model_id', $this->getAttribute('id'))
            ->where('lang_code', $lang)->first();

        if ($model) {
            $this->_translateIntl[$lang] = $model->toArray();
        }

        $translation = $this->_translateIntl[$lang] ?? [];
        Redis::connection()->command('set', [
            $this->generationCacheKey($lang),
            JsonSupport::encode($translation)
        ]);

        return $translation;
    }

    /**
     * @param $lang
     *
     * @return BaseEloquent
     */
    private function getIntlModel($lang): BaseEloquent
    {
        $className = $this->getIntlModelClass();
        /** @var Builder $builder */
        $builder = $className::query();

        /** @var BaseEloquent $model */
        $model = $builder
            ->where('model_id', $this->getAttribute('id'))
            ->where('lang_code', $lang)->first();

        return $model ?? new $className;
    }

    private function getCachedTranslateIntl($lang): ?array
    {
        $valuesJson = Redis::connection()->command('get', [$this->generationCacheKey($lang)]);
        return $valuesJson !== null ? JsonSupport::decode($valuesJson) : null;
    }

    private function generationCacheKey($lang): string
    {
        return $this->table . '_intl_' . Str::lower($lang) . $this->getAttribute('id');
    }
}
