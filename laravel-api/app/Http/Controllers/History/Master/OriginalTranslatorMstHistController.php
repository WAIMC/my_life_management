<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\OriginalTranslator\OriginalTranslatorMstHistListRequest;
use App\Http\Requests\History\Master\OriginalTranslator\StoreOriginalTranslatorMstHistRequest;
use App\Http\Requests\History\Master\OriginalTranslator\UpdateOriginalTranslatorMstHistRequest;
use App\Http\Resources\History\Master\OriginalTranslatorMstHistResource;
use App\Services\History\Master\OriginalTranslatorMstHistService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OriginalTranslatorMstHistController extends Controller
{
    /**
     * @var OriginalTranslatorMstHistService
     */
    protected OriginalTranslatorMstHistService $originalTranslatorMstHistService;

    /**
     * OriginalTranslatorMstHistController constructor.
     *
     * @param OriginalTranslatorMstHistService $originalTranslatorMstHistService
     */
    public function __construct(OriginalTranslatorMstHistService $originalTranslatorMstHistService)
    {
        $this->originalTranslatorMstHistService = $originalTranslatorMstHistService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param OriginalTranslatorMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(OriginalTranslatorMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->originalTranslatorMstHistService->getAll($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOriginalTranslatorMstHistRequest $request
     * @return OriginalTranslatorMstHistResource
     * @throws Exception
     */
    public function store(StoreOriginalTranslatorMstHistRequest $request): OriginalTranslatorMstHistResource
    {
        return $this->originalTranslatorMstHistService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return OriginalTranslatorMstHistResource
     */
    public function show(int $id): OriginalTranslatorMstHistResource
    {
        return $this->originalTranslatorMstHistService->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOriginalTranslatorMstHistRequest $request
     * @param int $id
     * @return OriginalTranslatorMstHistResource
     * @throws Exception
     */
    public function update(UpdateOriginalTranslatorMstHistRequest $request, int $id): OriginalTranslatorMstHistResource
    {
        return $this->originalTranslatorMstHistService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        return $this->originalTranslatorMstHistService->delete($id);
    }
}
