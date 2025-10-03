<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\OriginalTranslatorMst\ListOriginalTranslatorMstRequest;
use App\Http\Requests\Master\OriginalTranslatorMst\StoreOriginalTranslatorMstRequest;
use App\Http\Requests\Master\OriginalTranslatorMst\UpdateOriginalTranslatorMstRequest;
use App\Http\Requests\Master\OriginalTranslatorMst\DeleteOriginalTranslatorMstRequest;
use App\Services\Master\OriginalTranslatorMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginalTranslatorMstController extends Controller
{
    public function __construct(
        protected OriginalTranslatorMstService $originalTranslatorMst
    )
    {
    }
    
    /**
     * OriginalTranslatorMst list
     *
     * @param ListOriginalTranslatorMstRequest $request
     * @return JsonResource
     */
    public function list(ListOriginalTranslatorMstRequest $request): JsonResource
    {
        return $this->originalTranslatorMst->list($request->all());
    }

    /**
     * Store original translator mst
     *
     * @param StoreOriginalTranslatorMstRequest $request
     * @return int
     */
    public function store(StoreOriginalTranslatorMstRequest $request): int
    {
        return $this->originalTranslatorMst->store($request->all());
    }

    /**
     * Update original translator mst
     *
     * @param UpdateOriginalTranslatorMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateOriginalTranslatorMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->originalTranslatorMst->update($payload);
    }

    /**
     * Delete original translator mst
     *
     * @param DeleteOriginalTranslatorMstRequest $request
     * @return void
     */
    public function delete(DeleteOriginalTranslatorMstRequest $request): void
    {
        $this->originalTranslatorMst->delete($request->all());
    }
}
