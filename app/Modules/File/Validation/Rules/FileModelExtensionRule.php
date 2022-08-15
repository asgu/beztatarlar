<?php


namespace Modules\File\Validation\Rules;


use Illuminate\Contracts\Validation\Rule;
use Neti\Laravel\Files\Contracts\FileBaseInterface;
use Neti\Laravel\Files\Helpers\FileTypesHelper;

class FileModelExtensionRule implements Rule
{

    public function __construct($extension)
    {
        $this->setExtension($extension);
    }

    public array $extension = [];

    public function passes($attribute, $value): bool
    {
        $extension = !empty($this->extension) ? $this->extension : FileTypesHelper::getAllowExtensions();

        if ($value instanceof FileBaseInterface) {
            return FileTypesHelper::isFileInExt($value, $extension);
        }
        return false;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return __('validation.fileModelExtension', ['extension' => implode(', ', $this->extension)]);
    }

    /**
     * @param $extension
     *
     * @return FileModelExtensionRule
     */
    public function setExtension($extension): self
    {
        $this->extension = $extension;
        return $this;
    }
}
