<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\RoleMstHist\ListRoleMstHistRequest;
use App\Http\Requests\History\Master\RoleMstHist\StoreRoleMstHistRequest;
use App\Http\Requests\History\Master\RoleMstHist\UpdateRoleMstHistRequest;
use App\Http\Requests\History\Master\RoleMstHist\DeleteRoleMstHistRequest;
use App\Services\History\Master\RoleMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleMstHistController extends Controller
{
    public function __construct(
        protected RoleMstHistService $roleMstHist
    )
    {
    }
    
    /**
     * RoleMstHist list
     *
     * @param ListRoleMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListRoleMstHistRequest $request): JsonResource
    {
        return $this->roleMstHist->list($request->all());
    }

    /**
     * Store role mst hist
     *
     * @param StoreRoleMstHistRequest $request
     * @return int
     */
    public function store(StoreRoleMstHistRequest $request): int
    {
        return $this->roleMstHist->store($request->all());
    }

    /**
     * Update role mst hist
     *
     * @param UpdateRoleMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateRoleMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->roleMstHist->update($payload);
    }

    /**
     * Delete role mst hist
     *
     * @param DeleteRoleMstHistRequest $request
     * @return void
     */
    public function delete(DeleteRoleMstHistRequest $request): void
    {
        $this->roleMstHist->delete($request->all());
    }
}
