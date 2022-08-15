<?php


namespace Modules\Language\Services;


use App\Services\RedisServiceFacade;
use App\Support\JsonSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\Language\Dto\AppLanguageMessageSaveDto;
use Modules\Language\Factories\LanguageMessagesFactory;
use Modules\Language\Models\AppLanguage;
use Modules\Language\Models\AppLanguageMessage;
use Modules\Language\Repositories\LanguageMessagesRepository;
use Modules\Language\Validation\RulesValidation\AppLanguageMessageSaveValidation;
use Netibackend\Laravel\Exceptions\NotFoundException;

class LanguageMessagesService
{
    public const CACHE_PREFIX = 'app_language_messages_';

    private LanguageMessagesFactory $factory;
    private LanguageMessagesRepository $repository;

    public function __construct(
        LanguageMessagesFactory $factory,
        LanguageMessagesRepository $repository
    )
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function createDraw(): AppLanguageMessage
    {
        return $this->factory->create();
    }

    public function getTryById(int $id): AppLanguageMessage
    {
        $log = $this->repository->getById($id);

        if (!$log) {
            throw new NotFoundException(__('app.language.messagesNotFound'));
        }

        return $log;
    }

    public function getTryByCodeType($code, $type): AppLanguageMessage
    {
        $lang = $this->repository->getByCodeType($code, $type);

        if (!$lang) {
            throw new NotFoundException(__('app.language.messagesNotFound'));
        }

        return $lang;
    }

    public function getByCodeType($code, $type): ?AppLanguageMessage
    {
        return $this->repository->getByCodeType($code, $type);
    }

    public function populate(AppLanguageMessage $message, AppLanguageMessageSaveDto $dto): void
    {
        $message->fill($dto->toArray());
    }

    public function tryValidate(AppLanguageMessage $message): void
    {
        AppLanguageMessageSaveValidation::validateStatic($message, false);
    }

    public function save(AppLanguageMessage $message): void
    {
        $this->tryValidate($message);
        $this->beforeSave($message);

        $this->repository->save($message);
        $this->afterSave($message);
    }

    private function beforeSave(AppLanguageMessage $message): void
    {
        if (is_string($message->message_values)) {
            $message->message_values = JsonSupport::decode($message->message_values);
        }
    }

    private function afterSave(AppLanguageMessage $message): void
    {
        if ($message->type === AppLanguageMessage::TYPE_BACKEND) {
            $pattern = self::CACHE_PREFIX . Str::lower($message->code) . '*';
            RedisServiceFacade::deleteByPattern($pattern);
        }
    }

    public function clearBackendLanguagesCache($code)
    {
        $pattern = self::CACHE_PREFIX . Str::lower($code) . '*';
        RedisServiceFacade::deleteByPattern($pattern);
    }

    public function getListQuery(): Builder
    {
        return $this->repository->getListQuery();
    }

    public function getTryFrontendMessagesByAppLang(AppLanguage $appLanguage): AppLanguageMessage
    {
        return $this->getTryByCodeType($appLanguage->code, AppLanguageMessage::TYPE_FRONTEND);
    }

    public function getBackendMessagesByAppLang(AppLanguage $appLanguage): ?AppLanguageMessage
    {
        return $this->getByCodeType($appLanguage->code, AppLanguageMessage::TYPE_BACKEND);
    }

    public function generationCacheKey($locale, $group): string
    {
        return self::CACHE_PREFIX . Str::lower($locale) . '_' . $group;
    }
}
