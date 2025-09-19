<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\ApiMstHistInterface;
use App\Http\Resources\History\Master\ApiMstHistResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Exception;

class ApiMstHistService
{
    protected ApiMstHistInterface $apiMstHistRepository;

    /**
     * ApiMstHistService constructor
     *
     * @param ApiMstHistInterface $apiMstHistRepository
     */
    public function __construct(ApiMstHistInterface $apiMstHistRepository)
    {
        $this->apiMstHistRepository = $apiMstHistRepository;
    }

    /**
     * Get all API history records
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $apiHistories = $this->apiMstHistRepository->getAll($payload);
        return ApiMstHistResource::collection($apiHistories);
    }

    /**
     * Get API history by ID
     *
     * @param int $id
     * @return ApiMstHistResource
     * @throws Exception
     */
    public function findById(int $id): ApiMstHistResource
    {
        $apiHistory = $this->apiMstHistRepository->findById($id);

        if (!$apiHistory) {
            throw new Exception("API history record not found", 404);
        }

        return new ApiMstHistResource($apiHistory);
    }

    /**
     * Get API history by API master ID
     *
     * @param int $apiMstId
     * @return AnonymousResourceCollection
     */
    public function findByApiMstId(int $apiMstId): AnonymousResourceCollection
    {
        $apiHistories = $this->apiMstHistRepository->findByApiMstId($apiMstId);
        return ApiMstHistResource::collection($apiHistories);
    }

    /**
     * Create new API history record
     *
     * @param array $payload
     * @return ApiMstHistResource
     * @throws Exception
     */
    public function create(array $payload): ApiMstHistResource
    {
        try {
            DB::beginTransaction();

            $apiHistory = $this->apiMstHistRepository->create($payload);

            DB::commit();
            return new ApiMstHistResource($apiHistory);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
