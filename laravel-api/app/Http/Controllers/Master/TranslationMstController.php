<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Translation\DeleteTranslationMstRequest;
use App\Http\Requests\Master\Translation\StoreTranslationMstRequest;
use App\Http\Requests\Master\Translation\TranslationMstListRequest;
use App\Http\Requests\Master\Translation\UpdateTranslationMstRequest;
use App\Services\Master\TranslationMstService;

class TranslationMstController extends Controller
{
    protected $translationMstService;

    /**
     * Constructor
     *
     * @param TranslationMstService $translationMstService
     */
    public function __construct(TranslationMstService $translationMstService)
    {
        $this->translationMstService = $translationMstService;
    }

    /**
     * Get translation list
     *
     * @param TranslationMstListRequest $request
     * @return mixed
     */
    public function index(TranslationMstListRequest $request)
    {
        return $this->translationMstService->list($request->validated());
    }

    /**
     * Get translation by ID
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->translationMstService->getById($id);
    }

    /**
     * Get translations by language ID
     *
     * @param int $languageId
     * @return mixed
     */
    public function getByLanguageId(int $languageId)
    {
        return $this->translationMstService->getByLanguageId($languageId);
    }

    /**
     * Get translations by original ID
     *
     * @param int $originalId
     * @return mixed
     */
    public function getByOriginalId(int $originalId)
    {
        return $this->translationMstService->getByOriginalId($originalId);
    }

    /**
     * Store new translation
     *
     * @param StoreTranslationMstRequest $request
     * @return mixed
     */
    public function store(StoreTranslationMstRequest $request)
    {
        return $this->translationMstService->store($request->validated());
    }

    /**
     * Update translation
     *
     * @param UpdateTranslationMstRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UpdateTranslationMstRequest $request, int $id)
    {
        return $this->translationMstService->update($request->validated(), $id);
    }

    /**
     * Delete translation
     *
     * @param DeleteTranslationMstRequest $request
     * @param int $id
     * @return mixed
     */
    public function destroy(DeleteTranslationMstRequest $request, int $id)
    {
        return $this->translationMstService->delete($id);
    }
}
