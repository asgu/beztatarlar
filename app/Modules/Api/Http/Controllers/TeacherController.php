<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Dto\SearchDto;
use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Modules\Teacher\Serializers\TeacherSerializer;
use Modules\Teacher\Services\TeacherService;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class TeacherController extends BaseApiController
{
    /**
     * @var TeacherService
     */
    private TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * @param Request $request
     * @return array
     * @throws BindingResolutionException
     * @throws DataValidationException
     * @throws Exception
     */
    public function list(Request $request): array
    {
        $searchDto = new SearchDto();
        $searchDto->limit = 100;
        $searchDto->offset = 0;
        $teachers = $this->teacherService->getList($searchDto);

        return [
            'items' => TeacherSerializer::serialize($teachers->getModels())
        ];
    }
}
