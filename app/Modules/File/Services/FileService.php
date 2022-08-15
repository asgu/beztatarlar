<?php

namespace Modules\File\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Modules\File\Dto\TemporaryFileDto;
use Neti\Laravel\Files\Contracts\FileBaseInterface;
use Neti\Laravel\Files\Models\File;

class FileService extends \Neti\Laravel\Files\Services\FileService
{
    /**
     * @param $path
     * @return File
     * @throws Exception
     */
    public function createByPath($path): File
    {
        $file = $this->factory->createByPath($path);

        $file->uuid = $this->generateFileUuid();
        $this->produceFile($file);
        $file->setTemporaryUploadFile($path);
        $file->status = FileBaseInterface::STATUS_ACTIVE;

        return $file;
    }

    protected function _fileSavingProcess(File $file): void
    {
        // move file
        if (!$file->needSaveFile) {
            return;
        }

        if ($file->getTemporaryUploadFile() instanceof UploadedFile) {
            $this->filesystemFactory->disk()
                ->putFileAs($file->getStoredPath(), $file->getTemporaryUploadFile()->getPathname(), $file->getStoredName());
        }

        if (is_string($file->getTemporaryUploadFile())) {
            $this->filesystemFactory->disk()
                ->putFileAs($file->getStoredPath(), $file->getTemporaryUploadFile(), $file->getStoredName());
            $this->removeByPath($file->getTemporaryUploadFile());
        }

        $file->needSaveFile = false;
        $file->setTemporaryUploadFile(null);
    }

    /**
     * @param string $path
     */
    public function removeByPath(string $path)
    {
        unlink($path);
    }
}
