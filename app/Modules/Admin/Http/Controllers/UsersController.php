<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Modules\Certificate\Services\CertificateGeneratorService;
use Modules\Certificate\Services\CertificateService;
use Modules\User\Facades\UserFacade;
use Modules\User\Models\User;
use Modules\User\Services\UserAdminService;

class UsersController extends Controller
{
    private UserAdminService $adminService;

    public function __construct(UserAdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('Admin::users.index');
    }

    /**
     * @param User $user
     *
     * @return View
     */
    public function view(User $user): View
    {
        return view('Admin::users.view', [
            'user' => $user
        ]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->adminService->getFilteredDataTable()
            ->addColumn('profile.surname', function (User $user) {
                return UserFacade::surname($user);
            })
            ->addColumn('profile.name', function (User $user) {
                return UserFacade::name($user);
            })
            ->addColumn('profile.patronymic', function (User $user) {
                return UserFacade::patronymic($user);
            })
            ->addColumn('role', function (User $user) {
                return UserFacade::roleLabel($user->role);
            })
            ->addColumn('action', function(User $user) {
                return view('Admin::includes.actions', [
                    'id'        => $user->id,
                    'route'     => 'user',
                    'isView'    => true,
                    'isDeleted' => true,
                    'isEdit'    => false,
                ])->render();
            })
            ->blacklist(['action'])
            ->make(true);
    }
}
