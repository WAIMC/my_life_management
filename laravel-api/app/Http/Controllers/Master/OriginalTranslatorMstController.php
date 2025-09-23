<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\DeleteOriginalTranslatorMstRequest;
use App\Http\Requests\Master\OriginalTranslatorMstListRequest;
use App\Http\Requests\Master\StoreOriginalTranslatorMstRequest;
use App\Http\Requests\Master\UpdateOriginalTranslatorMstRequest;
use App\Http\Resources\Master\OriginalTranslatorMstResource;
use App\Services\Master\OriginalTranslatorMstService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OriginalTranslatorMstController extends Controller
{
    /**
     * @var OriginalTranslatorMstService
     */
    protected OriginalTranslatorMstService $originalTranslatorMstService;

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
     * @return AnonymousResourceCollection
     */
    public function list(OriginalTranslatorMstListRequest $request): AnonymousResourceCollection
    {
        return $this->originalTranslatorMstService->getList($request->validated());
    }

    /**
     * Get original translator by ID
     *
     * @param int $id
     * @return OriginalTranslatorMstResource
     */
    public function show(int $id): OriginalTranslatorMstResource
    {
        return $this->originalTranslatorMstService->getById($id);
    }

    /**
     * Create original translator
     *
     * @param StoreOriginalTranslatorMstRequest $request
     * @return OriginalTranslatorMstResource
     */
    public function store(StoreOriginalTranslatorMstRequest $request): OriginalTranslatorMstResource
    {
        return $this->originalTranslatorMstService->create($request->validated());
    }

    /**
     * Update original translator
     *
     * @param UpdateOriginalTranslatorMstRequest $request
     * @param int $id
     * @return OriginalTranslatorMstResource
     */
    public function update(UpdateOriginalTranslatorMstRequest $request, int $id): OriginalTranslatorMstResource
    {
        return $this->originalTranslatorMstService->update($request->validated(), $id);
    }

    /**
     * Delete original translator
     *
     * @param DeleteOriginalTranslatorMstRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(DeleteOriginalTranslatorMstRequest $request, int $id): JsonResponse
    {
        return $this->originalTranslatorMstService->delete($id);
    }
}
