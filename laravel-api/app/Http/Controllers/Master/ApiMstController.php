<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\Api\ApiMstDeleteRequest;
use App\Http\Requests\Master\Api\ApiMstListRequest;
use App\Http\Requests\Master\Api\ApiMstStoreRequest;
use App\Http\Requests\Master\Api\ApiMstUpdateRequest;
use App\Models\Master\ApiMst;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\Request;
use App\Services\Master\ApiMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ApiMstController extends Controller
{
    public function __construct(
        private ApiMstService $apiService
    )
    {
    }

    /**
     * ApiMst list
     *
     * @param ApiMstListRequest $request
     * @return JsonResource
     */
    public function list(ApiMstListRequest $request): JsonResource
    {
        return $this->apiService->list($request->all());
    }

    /**
     * Store api
     *
     * @param ApiMstStoreRequest $request
     * @return int
     */
    public function store(ApiMstStoreRequest $request): int
    {
        return $this->apiService->store($request->all());
    }

    /**
     * Update api
     *
     * @param ApiMstUpdateRequest $request
     * @param string $id
     * @return int
     */
    public function update(ApiMstUpdateRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->apiService->update($payload);
    }

    /**
     * Delete api
     *
     * @param ApiMstDeleteRequest $request
     * @return Void
     */
    public function delete(ApiMstDeleteRequest $request): Void
    {
        $this->apiService->delete($request->all());
    }
}
