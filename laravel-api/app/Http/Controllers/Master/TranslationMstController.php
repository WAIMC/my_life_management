<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\TranslationMst\ListTranslationMstRequest;
use App\Http\Requests\Master\TranslationMst\StoreTranslationMstRequest;
use App\Http\Requests\Master\TranslationMst\UpdateTranslationMstRequest;
use App\Http\Requests\Master\TranslationMst\DeleteTranslationMstRequest;
use App\Services\Master\TranslationMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstController extends Controller
{
    public function __construct(
        protected TranslationMstService $translationMst
    )
    {
    }
    
    /**
     * TranslationMst list
     *
     * @param ListTranslationMstRequest $request
     * @return JsonResource
     */
    public function list(ListTranslationMstRequest $request): JsonResource
    {
        return $this->translationMst->list($request->all());
    }

    /**
     * Store translation mst
     *
     * @param StoreTranslationMstRequest $request
     * @return int
     */
    public function store(StoreTranslationMstRequest $request): int
    {
        return $this->translationMst->store($request->all());
    }

    /**
     * Update translation mst
     *
     * @param UpdateTranslationMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateTranslationMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->translationMst->update($payload);
    }

    /**
     * Delete translation mst
     *
     * @param DeleteTranslationMstRequest $request
     * @return void
     */
    public function delete(DeleteTranslationMstRequest $request): void
    {
        $this->translationMst->delete($request->all());
    }
}
