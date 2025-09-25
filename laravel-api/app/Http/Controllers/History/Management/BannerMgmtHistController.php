<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\Banner\BannerMgmtHistListRequest;
use App\Http\Requests\History\Management\Banner\StoreBannerMgmtHistRequest;
use App\Services\History\Management\BannerMgmtHistService;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerMgmtHistController extends Controller
{
    /**
     * BannerMgmtHistController constructor
     *
     * @param BannerMgmtHistService $bannerMgmtHistService
     */
    public function __construct(protected BannerMgmtHistService $bannerMgmtHistService)
    {}

    /**
     * Get a listing of banner histories
     *
     * @param BannerMgmtHistListRequest $request
     * @return JsonResource
     */
    public function index(BannerMgmtHistListRequest $request): JsonResource
    {
        return $this->bannerMgmtHistService->list($request->all());
    }

    /**
     * Create new banner history records (batch)
     *
     * @param StoreBannerMgmtHistRequest $request
     * @return bool
     */
    public function store(StoreBannerMgmtHistRequest $request): bool
    {
        return $this->bannerMgmtHistService->store($request->all());
    }

    /**
     * Update banner history records (batch)
     *
     * @param StoreBannerMgmtHistRequest $request
     * @return bool
     */
    public function update(StoreBannerMgmtHistRequest $request): bool
    {
        return $this->bannerMgmtHistService->update($request->all());
    }

    /**
     * Delete banner history records (batch)
     *
     * @param StoreBannerMgmtHistRequest $request
     * @return int
     */
    public function destroy(StoreBannerMgmtHistRequest $request): int
    {
        // Expecting $request->all()['ids'] to be an array of ids
        return $this->bannerMgmtHistService->delete($request->all());
    }
}
