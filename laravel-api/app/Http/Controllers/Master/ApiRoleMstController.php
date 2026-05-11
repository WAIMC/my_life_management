<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\ApiRoleMst\ListApiRoleMstRequest;
use App\Http\Requests\Master\ApiRoleMst\UpdateApiRoleMstRequest;
use App\Services\Master\ApiRoleMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiRoleMstController extends Controller
{
    public function __construct(
        protected ApiRoleMstService $apiRoleMst
    )
    {
    }
    
    /**
     * ApiRoleMst list
     *
     * @param ListApiRoleMstRequest $request
     * @return JsonResource
     */
    public function list(ListApiRoleMstRequest $request): JsonResource
    {
        return $this->apiRoleMst->list($request->all());
    }

    /**
     * Update api role mst
     *
     * @param UpdateApiRoleMstRequest $request
     * @return bool
     */
    public function update(UpdateApiRoleMstRequest $request): bool
    {
        return $this->apiRoleMst->update($request->all());
    }
}
