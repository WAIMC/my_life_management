<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\AdminMst\ListAdminMstRequest;
use App\Http\Requests\Master\AdminMst\StoreAdminMstRequest;
use App\Http\Requests\Master\AdminMst\UpdateAdminMstRequest;
use App\Http\Requests\Master\AdminMst\DeleteAdminMstRequest;
use App\Services\Master\AdminMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMstController extends Controller
{
    public function __construct(
        protected AdminMstService $adminMst
    )
    {
    }
    
    /**
     * AdminMst list
     *
     * @param ListAdminMstRequest $request
     * @return JsonResource
     */
    public function list(ListAdminMstRequest $request): JsonResource
    {
        return $this->adminMst->list($request->all());
    }

    /**
     * Store admin mst
     *
     * @param StoreAdminMstRequest $request
     * @return int
     */
    public function store(StoreAdminMstRequest $request): int
    {
        return $this->adminMst->store($request->all());
    }

    /**
     * Update admin mst
     *
     * @param UpdateAdminMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateAdminMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->adminMst->update($payload);
    }

    /**
     * Delete admin mst
     *
     * @param DeleteAdminMstRequest $request
     * @return void
     */
    public function delete(DeleteAdminMstRequest $request): void
    {
        $this->adminMst->delete($request->all());
    }
}
