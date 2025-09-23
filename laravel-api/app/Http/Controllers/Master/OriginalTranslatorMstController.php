<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\DeleteOriginalTranslatorMstRequest;
use App\Http\Requests\Master\OriginalTranslatorMstListRequest;
use App\Http\Requests\Master\StoreOriginalTranslatorMstRequest;
use App\Http\Requests\Master\UpdateOriginalTranslatorMstRequest;
use App\Services\Master\OriginalTranslatorMstService;
use Illuminate\Http\JsonResponse;

class OriginalTranslatorMstController extends Controller
{
    /**
     * @var OriginalTranslatorMstService
     */
    protected $originalTranslatorMstService;

    /**
     * OriginalTranslatorMstController constructor.
     *
     * @param OriginalTranslatorMstService $originalTranslatorMstService
     */
    public function __construct(OriginalTranslatorMstService $originalTranslatorMstService)
    {
        $this->originalTranslatorMstService = $originalTranslatorMstService;
    }

    /**
     * Get list of original translators
     *
     * @param OriginalTranslatorMstListRequest $request
     * @return JsonResponse
     */
    public function list(OriginalTranslatorMstListRequest $request)
    {
        return $this->originalTranslatorMstService->getList($request->validated());
    }

    /**
     * Get original translator by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        return $this->originalTranslatorMstService->getById($id);
    }

    /**
     * Create original translator
     *
     * @param StoreOriginalTranslatorMstRequest $request
     * @return JsonResponse
     */
    public function store(StoreOriginalTranslatorMstRequest $request)
    {
        return $this->originalTranslatorMstService->create($request->validated());
    }

    /**
     * Update original translator
     *
     * @param UpdateOriginalTranslatorMstRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateOriginalTranslatorMstRequest $request, int $id)
    {
        return $this->originalTranslatorMstService->update($request->validated(), $id);
    }

    /**
     * Delete original translator
     *
     * @param DeleteOriginalTranslatorMstRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(DeleteOriginalTranslatorMstRequest $request, int $id)
    {
        return $this->originalTranslatorMstService->delete($id);
    }
}
