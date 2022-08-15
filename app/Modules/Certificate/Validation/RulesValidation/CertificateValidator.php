<?php


namespace Modules\Certificate\Validation\RulesValidation;


use Modules\File\Validation\Rules\FileModelExtensionRule;
use Neti\Laravel\Files\Helpers\FileTypesHelper;
use Netibackend\Laravel\Validation\ModelValidator;

class CertificateValidator extends ModelValidator
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
            'file' => [
                'required',
                new FileModelExtensionRule(FileTypesHelper::PDF_EXTENSIONS)
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'created_at.date_format' => __('validation.dateFormat', ['format' => 'yyyy-mm-dd h:m:s'])
        ];
    }

}
