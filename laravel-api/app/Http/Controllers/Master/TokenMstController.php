<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\TokenMst\ListTokenMstRequest;
use App\Http\Requests\Master\TokenMst\StoreTokenMstRequest;
use App\Http\Requests\Master\TokenMst\UpdateTokenMstRequest;
use App\Http\Requests\Master\TokenMst\DeleteTokenMstRequest;
use App\Services\Master\TokenMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenMstController extends Controller
{
    public function __construct(
        protected TokenMstService $tokenMst
    )
    {
    }
    
    /**
     * TokenMst list
     *
     * @param ListTokenMstRequest $request
     * @return JsonResource
     */
    public function list(ListTokenMstRequest $request): JsonResource
    {
        return $this->tokenMst->list($request->all());
    }

    /**
     * Store token mst
     *
     * @param StoreTokenMstRequest $request
     * @return int
     */
    public function store(StoreTokenMstRequest $request): int
    {
        return $this->tokenMst->store($request->all());
    }

    /**
     * Update token mst
     *
     * @param UpdateTokenMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateTokenMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->tokenMst->update($payload);
    }

    /**
     * Delete token mst
     *
     * @param DeleteTokenMstRequest $request
     * @return void
     */
    public function delete(DeleteTokenMstRequest $request): void
    {
        $this->tokenMst->delete($request->all());
    }
}
