<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\ApiMstHist\ApiMstHistListRequest;
use App\Http\Requests\History\Master\ApiMstHist\StoreApiMstHistRequest;
use App\Http\Resources\History\Master\ApiMstHistResource;
use App\Services\History\Master\ApiMstHistService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiMstHistController extends Controller
{
    protected ApiMstHistService $apiMstHistService;

    /**
     * ApiMstHistController constructor
     *
     * @param ApiMstHistService $apiMstHistService
     */
    public function __construct(ApiMstHistService $apiMstHistService)
    {
        $this->apiMstHistService = $apiMstHistService;
    }

    /**
     * Get a listing of API history records
     *
     * @param ApiMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(ApiMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->apiMstHistService->getAll($request->validated());
    }

    /**
     * Get API history by ID
     *
     * @param int $id
     * @return ApiMstHistResource
     * @throws Exception
     */
    public function show(int $id): ApiMstHistResource
    {
        return $this->apiMstHistService->findById($id);
    }

    /**
     * Get API history by API master ID
     *
     * @param int $apiMstId
     * @return AnonymousResourceCollection
     */
    public function getByApiMstId(int $apiMstId): AnonymousResourceCollection
    {
        return $this->apiMstHistService->findByApiMstId($apiMstId);
    }

    /**
     * Create a new API history record
     *
     * @param StoreApiMstHistRequest $request
     * @return ApiMstHistResource
     * @throws Exception
     */
    public function store(StoreApiMstHistRequest $request): ApiMstHistResource
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->apiMstHistService->create($data);
    }
}
