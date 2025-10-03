<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\TranslationMstHist\ListTranslationMstHistRequest;
use App\Http\Requests\History\Master\TranslationMstHist\StoreTranslationMstHistRequest;
use App\Http\Requests\History\Master\TranslationMstHist\UpdateTranslationMstHistRequest;
use App\Http\Requests\History\Master\TranslationMstHist\DeleteTranslationMstHistRequest;
use App\Services\History\Master\TranslationMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstHistController extends Controller
{
    public function __construct(
        protected TranslationMstHistService $translationMstHist
    )
    {
    }
    
    /**
     * TranslationMstHist list
     *
     * @param ListTranslationMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListTranslationMstHistRequest $request): JsonResource
    {
        return $this->translationMstHist->list($request->all());
    }

    /**
     * Store translation mst hist
     *
     * @param StoreTranslationMstHistRequest $request
     * @return int
     */
    public function store(StoreTranslationMstHistRequest $request): int
    {
        return $this->translationMstHist->store($request->all());
    }

    /**
     * Update translation mst hist
     *
     * @param UpdateTranslationMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateTranslationMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->translationMstHist->update($payload);
    }

    /**
     * Delete translation mst hist
     *
     * @param DeleteTranslationMstHistRequest $request
     * @return void
     */
    public function delete(DeleteTranslationMstHistRequest $request): void
    {
        $this->translationMstHist->delete($request->all());
    }
}
