<?php


namespace Modules\Language\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Language\Models\AppLanguageMessage;

class LanguageMessagesRepository
{
    public function save(AppLanguageMessage $message): void
    {
        $message->save();
    }

    public function getById($id): ?AppLanguageMessage
    {
        return AppLanguageMessage::find($id);
    }

    public function getByCodeType(string $code, string $type): ?AppLanguageMessage
    {
        return AppLanguageMessage::where([
            'code' => $code,
            'type' => $type,
        ])->first();
    }

    public function getListQuery(): Builder
    {
        return AppLanguageMessage::query();
    }
}
