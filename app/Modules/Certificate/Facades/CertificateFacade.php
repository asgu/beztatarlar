<?php


namespace Modules\Certificate\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Certificate\Models\Certificate;
use Modules\Certificate\Services\CertificateService;

/**
 * Class CertificateFacade
 * @package Modules\Certificate\Facades
 * @method static bool isActive(Certificate $certificate)
 */
class CertificateFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CertificateService::class;
    }

}
