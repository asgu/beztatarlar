<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Dto\SearchDto;
use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Modules\Position\Serializers\PositionSerializer;
use Modules\Position\Services\PositionService;
use Netibackend\Laravel\SearchProvider\Serializers\PaginationMetaSerializer;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class PositionController extends BaseApiController
{
    /**
     * @var PositionService
     */
    private PositionService $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
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
        $resultProvider = $this->positionService->getAllActive($searchDto);

        return [
            'items' => PositionSerializer::serialize($resultProvider->getModels()),
        ];
    }
}
