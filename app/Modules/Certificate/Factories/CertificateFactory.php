<?php


namespace Modules\Certificate\Factories;


use Exception;
use Modules\Certificate\Models\Certificate;
use Modules\User\Models\User;
use Netibackend\Laravel\Helpers\DateHelper;

class CertificateFactory
{
    /**
     * @param User $createdByUser
     * @return Certificate
     * @throws Exception
     */
    public function create(User $createdByUser): Certificate
    {
        $certificate = new Certificate();
        $certificate->created_at = DateHelper::now();
        $certificate->user_id = $createdByUser->id;
        $certificate->setRelation('createdByUser', $createdByUser);

        return $certificate;
    }
}
