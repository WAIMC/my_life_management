<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\AdminMstHist\ListAdminMstHistRequest;
use App\Http\Requests\History\Master\AdminMstHist\StoreAdminMstHistRequest;
use App\Http\Requests\History\Master\AdminMstHist\UpdateAdminMstHistRequest;
use App\Http\Requests\History\Master\AdminMstHist\DeleteAdminMstHistRequest;
use App\Services\History\Master\AdminMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMstHistController extends Controller
{
    public function __construct(
        protected AdminMstHistService $adminMstHist
    )
    {
    }
    
    /**
     * AdminMstHist list
     *
     * @param ListAdminMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListAdminMstHistRequest $request): JsonResource
    {
        return $this->adminMstHist->list($request->all());
    }

    /**
     * Store admin mst hist
     *
     * @param StoreAdminMstHistRequest $request
     * @return int
     */
    public function store(StoreAdminMstHistRequest $request): int
    {
        return $this->adminMstHist->store($request->all());
    }

    /**
     * Update admin mst hist
     *
     * @param UpdateAdminMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateAdminMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->adminMstHist->update($payload);
    }

    /**
     * Delete admin mst hist
     *
     * @param DeleteAdminMstHistRequest $request
     * @return void
     */
    public function delete(DeleteAdminMstHistRequest $request): void
    {
        $this->adminMstHist->delete($request->all());
    }
}
