<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Dto\SearchDto;
use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Modules\Mukhtasibat\Serializers\MukhtasibatSerializer;
use Modules\Mukhtasibat\Services\MukhtasibatService;
use Netibackend\Laravel\SearchProvider\Serializers\PaginationMetaSerializer;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class MukhtasibatController extends BaseApiController
{
    /**
     * @var MukhtasibatService
     */
    private MukhtasibatService $mukhtasibatService;

    public function __construct(MukhtasibatService $mukhtasibatService)
    {
        $this->mukhtasibatService = $mukhtasibatService;
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
        $provider = $this->mukhtasibatService->getAllActive($searchDto);

        return [
            'items' => MukhtasibatSerializer::serialize($provider->getModels()),
        ];
    }
}
