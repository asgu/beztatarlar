<?php


namespace Modules\ActivityStatus\Services;


use JetBrains\PhpStorm\ArrayShape;
use Modules\ActivityStatus\Facades\ActivityStatusFacade;

class ActivityStatusService
{
    /**
     * @return string[]
     */
    #[ArrayShape([
        ActivityStatusFacade::STATUS_INACTIVE => "string",
        ActivityStatusFacade::STATUS_ACTIVE => "string"
    ])]
    public function statuses(): array
    {
        return [
            ActivityStatusFacade::STATUS_INACTIVE => 'Заблокирован',
            ActivityStatusFacade::STATUS_ACTIVE   => 'Активный',
        ];
    }

    /**
     * @param string $status
     * @return string
     */
    public function statusLabel(string $status): string
    {
        return $this->statuses()[$status] ?? $status;
    }
}
