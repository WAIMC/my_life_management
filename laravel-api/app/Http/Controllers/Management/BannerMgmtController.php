<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Banner\BannerMgmtListRequest;
use App\Http\Requests\Management\Banner\StoreBannerMgmtRequest;
use App\Http\Requests\Management\Banner\UpdateBannerMgmtRequest;
use App\Http\Requests\Management\Banner\DeleteBannerMgmtRequest;
use App\Http\Resources\Management\BannerMgmtResource;
use App\Services\Management\BannerMgmtService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BannerMgmtController extends Controller
{
    protected BannerMgmtService $bannerMgmtService;

    /**
     * BannerMgmtController constructor
     *
     * @param BannerMgmtService $bannerMgmtService
     */
    public function __construct(BannerMgmtService $bannerMgmtService)
    {
        $this->bannerMgmtService = $bannerMgmtService;
    }

    /**
     * Get a listing of banners
     *
     * @param BannerMgmtListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(BannerMgmtListRequest $request): AnonymousResourceCollection
    {
        return $this->bannerMgmtService->getAll($request->validated());
    }

    /**
     * Get banner by ID
     *
     * @param int $id
     * @return BannerMgmtResource
     * @throws Exception
     */
    public function show(int $id): BannerMgmtResource
    {
        return $this->bannerMgmtService->findById($id);
    }

    /**
     * Create a new banner
     *
     * @param StoreBannerMgmtRequest $request
     * @return BannerMgmtResource
     * @throws Exception
     */
    public function store(StoreBannerMgmtRequest $request): BannerMgmtResource
    {
        return $this->bannerMgmtService->create($request->validated());
    }

    /**
     * Update an existing banner
     *
     * @param UpdateBannerMgmtRequest $request
     * @param int $id
     * @return BannerMgmtResource
     * @throws Exception
     */
    public function update(UpdateBannerMgmtRequest $request, int $id): BannerMgmtResource
    {
        return $this->bannerMgmtService->update($id, $request->validated());
    }

    /**
     * Delete a banner
     *
     * @param DeleteBannerMgmtRequest $request
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function destroy(DeleteBannerMgmtRequest $request, int $id): array
    {
        return $this->bannerMgmtService->delete($id, $request->validated());
    }
}
