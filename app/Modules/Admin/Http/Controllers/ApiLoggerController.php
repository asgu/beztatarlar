<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Netibackend\Laravel\Modules\ApiLogger\Models\ApiLogger;
use Netibackend\Laravel\Modules\ApiLogger\Services\ApiLoggerService;
use Yajra\DataTables\Facades\DataTables;

class ApiLoggerController extends Controller
{
    public ApiLoggerService $apiLoggerService;

    public function __construct(ApiLoggerService $apiLoggerService)
    {
        $this->apiLoggerService = $apiLoggerService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return DataTables::eloquent($this->apiLoggerService->getQuery())
            ->addColumn('action', function (ApiLogger $log) {
                return view('Admin::apiLogger.includes.actions', [
                    'id'    => $log->id,
                    'route' => 'apiLogger',
                ])->render();
            })
            ->make(true);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('Admin::apiLogger.index');
    }

    /**
     * @param $id
     *
     * @return Application|Factory|View
     */
    public function view($id)
    {
        return view('Admin::apiLogger.show', [
            'log' => $this->apiLoggerService->getTryById($id)
        ]);
    }
}
