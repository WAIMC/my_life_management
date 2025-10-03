<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\ApiMst\ListApiMstRequest;
use App\Http\Requests\Master\ApiMst\StoreApiMstRequest;
use App\Http\Requests\Master\ApiMst\UpdateApiMstRequest;
use App\Http\Requests\Master\ApiMst\DeleteApiMstRequest;
use App\Services\Master\ApiMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiMstController extends Controller
{
    public function __construct(
        protected ApiMstService $apiMst
    )
    {
    }
    
    /**
     * ApiMst list
     *
     * @param ListApiMstRequest $request
     * @return JsonResource
     */
    public function list(ListApiMstRequest $request): JsonResource
    {
        return $this->apiMst->list($request->all());
    }

    /**
     * Store api mst
     *
     * @param StoreApiMstRequest $request
     * @return int
     */
    public function store(StoreApiMstRequest $request): int
    {
        return $this->apiMst->store($request->all());
    }

    /**
     * Update api mst
     *
     * @param UpdateApiMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateApiMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->apiMst->update($payload);
    }

    /**
     * Delete api mst
     *
     * @param DeleteApiMstRequest $request
     * @return void
     */
    public function delete(DeleteApiMstRequest $request): void
    {
        $this->apiMst->delete($request->all());
    }
}
