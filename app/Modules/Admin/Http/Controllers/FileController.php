<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Neti\Laravel\Files\Models\File;
use Neti\Laravel\Files\Services\FileService;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function uploadFile(Request $request)
    {
        if (!$request->hasFile('upload')) {
            return;
        }

        $file = $this->fileService->createByUploadedFile($request->file('upload'));
        $this->prepareCkEditorResponse($request, $file);
    }

    private function prepareCkEditorResponse(Request $request, File $file)
    {
        $url = $file->getFullUrl();
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }
}
