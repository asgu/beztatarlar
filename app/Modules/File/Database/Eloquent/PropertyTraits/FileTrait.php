<?php

namespace Modules\File\Database\Eloquent\PropertyTraits;


use Neti\Laravel\Files\Models\File;

/**
 * @property File|null $removeFile
 */
trait FileTrait
{
    public ?File $removeFile = null;

    public function setFile(File $file): void
    {
        $this->markRemoveFile();
        $this->setRelation('file', $file);
    }

    public function markRemoveFile(): void
    {
        $this->removeFile = $this->file;

        $this->file_uuid = null;
        $this->setRelation('file', null);
    }
}
