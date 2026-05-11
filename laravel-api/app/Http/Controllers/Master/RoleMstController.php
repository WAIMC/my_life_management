<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\RoleMst\ListRoleMstRequest;
use App\Http\Requests\Master\RoleMst\StoreRoleMstRequest;
use App\Http\Requests\Master\RoleMst\UpdateRoleMstRequest;
use App\Http\Requests\Master\RoleMst\DeleteRoleMstRequest;
use App\Services\Master\RoleMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleMstController extends Controller
{
    public function __construct(
        protected RoleMstService $roleMst
    )
    {
    }
    
    /**
     * RoleMst list
     *
     * @param ListRoleMstRequest $request
     * @return JsonResource
     */
    public function list(ListRoleMstRequest $request): JsonResource
    {
        return $this->roleMst->list($request->all());
    }

    /**
     * Store role mst
     *
     * @param StoreRoleMstRequest $request
     * @return int
     */
    public function store(StoreRoleMstRequest $request): int
    {
        return $this->roleMst->store($request->all());
    }

    /**
     * Update role mst
     *
     * @param UpdateRoleMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateRoleMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->roleMst->update($payload);
    }

    /**
     * Delete role mst
     *
     * @param DeleteRoleMstRequest $request
     * @return void
     */
    public function delete(DeleteRoleMstRequest $request): void
    {
        $this->roleMst->delete($request->all());
    }
}
