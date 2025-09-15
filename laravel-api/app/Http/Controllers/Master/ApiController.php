<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\Api\ApiDeleteRequest;
use App\Http\Requests\Master\Api\ApiListRequest;
use App\Http\Requests\Master\Api\ApiStoreRequest;
use App\Http\Requests\Master\Api\ApiUpdateRequest;
use App\Models\Master\Api;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\Request;
use App\Services\Master\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ApiController extends Controller
{
    public function __construct(
        private ApiService $apiService
    )
    {
    }

    /**
     * Api list
     *
     * @param ApiListRequest $request
     * @return JsonResource
     */
    public function list(ApiListRequest $request): JsonResource
    {
        return $this->apiService->list($request->all());
    }

    /**
     * Store api
     *
     * @param ApiStoreRequest $request
     * @return int
     */
    public function store(ApiStoreRequest $request): int
    {
        return $this->apiService->store($request->all());
    }

    /**
     * Update api
     *
     * @param ApiUpdateRequest $request
     * @param string $id
     * @return int
     */
    public function update(ApiUpdateRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->apiService->update($payload);
    }

    /**
     * Delete api
     *
     * @param ApiDeleteRequest $request
     * @return Void
     */
    public function delete(ApiDeleteRequest $request): Void
    {
        $this->apiService->delete($request->all());
    }
}
