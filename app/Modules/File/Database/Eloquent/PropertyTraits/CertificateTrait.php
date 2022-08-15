<?php


namespace Modules\File\Database\Eloquent\PropertyTraits;


use Neti\Laravel\Files\Models\File;

trait CertificateTrait
{
    public ?File $removeCertificate = null;

    public function setCertificate(File $file): void
    {
        $this->markRemoveCertificate();
        $this->setRelation('certificate', $file);
    }

    public function markRemoveCertificate(): void
    {
        $this->removeCertificate = $this->certificate;

        $this->certificate_uuid = null;
        $this->setRelation('certificate', null);
    }
}
