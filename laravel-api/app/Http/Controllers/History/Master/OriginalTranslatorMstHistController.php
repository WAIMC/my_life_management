<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\OriginalTranslatorMstHist\ListOriginalTranslatorMstHistRequest;
use App\Http\Requests\History\Master\OriginalTranslatorMstHist\StoreOriginalTranslatorMstHistRequest;
use App\Http\Requests\History\Master\OriginalTranslatorMstHist\UpdateOriginalTranslatorMstHistRequest;
use App\Http\Requests\History\Master\OriginalTranslatorMstHist\DeleteOriginalTranslatorMstHistRequest;
use App\Services\History\Master\OriginalTranslatorMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginalTranslatorMstHistController extends Controller
{
    public function __construct(
        protected OriginalTranslatorMstHistService $originalTranslatorMstHist
    )
    {
    }
    
    /**
     * OriginalTranslatorMstHist list
     *
     * @param ListOriginalTranslatorMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListOriginalTranslatorMstHistRequest $request): JsonResource
    {
        return $this->originalTranslatorMstHist->list($request->all());
    }

    /**
     * Store original translator mst hist
     *
     * @param StoreOriginalTranslatorMstHistRequest $request
     * @return int
     */
    public function store(StoreOriginalTranslatorMstHistRequest $request): int
    {
        return $this->originalTranslatorMstHist->store($request->all());
    }

    /**
     * Update original translator mst hist
     *
     * @param UpdateOriginalTranslatorMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateOriginalTranslatorMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->originalTranslatorMstHist->update($payload);
    }

    /**
     * Delete original translator mst hist
     *
     * @param DeleteOriginalTranslatorMstHistRequest $request
     * @return void
     */
    public function delete(DeleteOriginalTranslatorMstHistRequest $request): void
    {
        $this->originalTranslatorMstHist->delete($request->all());
    }
}
