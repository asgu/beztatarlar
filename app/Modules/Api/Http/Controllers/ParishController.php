<?php


namespace Modules\Api\Http\Controllers;


use App\Api\Dto\SearchDto;
use App\Api\Routing\BaseApiController;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Modules\Parish\Serializers\ParishSerializer;
use Modules\Parish\Services\ParishService;
use Netibackend\Laravel\SearchProvider\Serializers\PaginationMetaSerializer;
use Netibackend\Laravel\Validation\Exceptions\DataValidationException;

class ParishController extends BaseApiController
{
    /**
     * @var ParishService
     */
    private ParishService $parishService;

    public function __construct(ParishService $parishService)
    {
        $this->parishService = $parishService;
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
        $provider = $this->parishService->getAllActive($searchDto);

        return [
            'items' => ParishSerializer::serialize($provider->getModels()),
        ];
    }
}
