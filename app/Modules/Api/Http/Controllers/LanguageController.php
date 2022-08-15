<?php

namespace Modules\Api\Http\Controllers;

use App\Api\Routing\BaseApiController;
use Exception;
use Modules\Language\Models\AppLanguageMessage;
use Modules\Language\Serializers\AppLanguageMessageSerializer;
use Modules\Language\Serializers\AppLanguageMessageAgreementsSerializer;
use Modules\Language\Serializers\AppLanguageSerializer;
use Modules\Language\Services\LanguageMessagesService;
use Modules\Language\Services\LanguageService;

class LanguageController extends BaseApiController
{
    /**
     * @var LanguageService
     */
    protected LanguageService $languageService;

    /**
     * @var LanguageMessagesService
     */
    protected LanguageMessagesService $languageMessagesService;

    public function __construct(
        LanguageService $languageService,
        LanguageMessagesService $languageMessagesService,
    )
    {
        $this->languageService = $languageService;
        $this->languageMessagesService = $languageMessagesService;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function appLanguagesList(): array
    {
        $list = $this->languageService->getAppLanguagesList();
        return AppLanguageSerializer::serialize($list);
    }

    /**
     * @param $code
     *
     * @return array
     * @throws Exception
     */
    public function appLanguagesMessages($code): array
    {
        $appLanguages = $this->languageService->getTryByCode($code);
        $messages = $this->languageMessagesService->getTryFrontendMessagesByAppLang($appLanguages);

        return AppLanguageMessageSerializer::serialize($messages);
    }

    /**
     * @param $code
     * @return array
     *
     * @throws Exception
     */
    public function appLanguagesAgreements($code): array
    {
        $appAgreements = $this->languageMessagesService->getTryByCodeType($code, AppLanguageMessage::TYPE_FRONTEND_ARGUMENTS);
        return AppLanguageMessageAgreementsSerializer::serialize($appAgreements);
    }

    /**
     * @param $code
     * @return array
     *
     * @throws Exception
     */
    public function appLanguagesPrivacyPolicy($code): array
    {
        $appAgreements = $this->languageMessagesService->getTryByCodeType($code, AppLanguageMessage::TYPE_FRONTEND_PRIVACY_POLICY);
        return AppLanguageMessageAgreementsSerializer::serialize($appAgreements);
    }
}
