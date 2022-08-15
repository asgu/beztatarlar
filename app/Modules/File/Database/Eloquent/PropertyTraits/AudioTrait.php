<?php


namespace Modules\File\Database\Eloquent\PropertyTraits;


use Neti\Laravel\Files\Models\File;

/**
 * Trait AudioTrait
 * @package Modules\File\Database\Eloquent\PropertyTraits
 * @property File|null $removeAudio
 */
trait AudioTrait
{
    public ?File $removeAudio = null;

    public function setAudio(File $audio): void
    {
        $this->markRemoveAudio();
        $this->setRelation('audio', $audio);
    }

    public function markRemoveAudio(): void
    {
        $this->removeAudio = $this->audio;
        $this->audio_uuid = null;
        $this->setRelation('audio', null);
    }
}
