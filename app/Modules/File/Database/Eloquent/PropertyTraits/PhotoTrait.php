<?php


namespace Modules\File\Database\Eloquent\PropertyTraits;


use Neti\Laravel\Files\Models\File;

/**
 * Trait PhotoTrait
 * @package app\modules\lesson\traits
 * @property File|null $removePhoto
 * @property File|null $photo
 */
trait PhotoTrait
{
    public ?File $removePhoto = null;

    /**
     * @param File $file
     */
    public function setPhoto(File $file): void
    {
        $this->markRemovePhoto();
        $this->setRelation('photo', $file);
    }

    /**
     * Mark photo for deleted
     */
    public function markRemovePhoto(): void
    {
        $this->removePhoto = $this->photo;
        $this->photo_uuid = null;
        $this->setRelation('photo', null);
    }
}
