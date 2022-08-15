<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Certificate\Dto\CertificateDto;
use Modules\Certificate\Models\Certificate;
use Modules\Certificate\Services\CertificateService;
use Modules\Lesson\Modules\Topic\Models\LessonTopic;
use Modules\User\Modules\Profile\Facades\UserProfileFacade;
use Modules\User\Modules\Profile\Models\UserProfile;
use Modules\User\Modules\Profile\Services\UserProfileService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class CertificateController extends Controller
{
    /**
     * @var UserProfileService
     */
    private UserProfileService $profileService;

    public function __construct(UserProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function data(): JsonResponse
    {
        return $this->profileService->getCertificatesFilteredDataTable()
            ->addColumn('created_at', function (UserProfile $profile) {
                return DateHelper::formatDate($profile->certificate->created_at);
            })
            ->addColumn('fio', function (UserProfile $profile) {
                return UserProfileFacade::fullName($profile);
            })
            ->addColumn('action', function(UserProfile $profile) {
                return view('Admin::includes.actions', [
                    'id' => $profile->id,
                    'route' => 'certificate',
                    'isView'    => true,
                    'isDeleted' => true,
                    'isEdit'    => false,
                ])->render();
            })
            ->make(true);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('Admin::certificate.index');
    }

    /**
     * @param $id
     * @return View
     */
    public function view($id): View
    {
        $profile = $this->profileService->tryGetById($id);
        return view('Admin::certificate.view', ['profile' => $profile]);
    }

}
