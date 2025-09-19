<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\Banner\BannerMgmtHistListRequest;
use App\Http\Requests\History\Management\Banner\StoreBannerMgmtHistRequest;
use App\Http\Resources\History\Management\BannerMgmtHistResource;
use App\Services\History\Management\BannerMgmtHistService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BannerMgmtHistController extends Controller
{
    protected BannerMgmtHistService $bannerMgmtHistService;

    /**
     * BannerMgmtHistController constructor
     *
     * @param BannerMgmtHistService $bannerMgmtHistService
     */
    public function __construct(BannerMgmtHistService $bannerMgmtHistService)
    {
        $this->bannerMgmtHistService = $bannerMgmtHistService;
    }

    /**
     * Get a listing of banner histories
     *
     * @param BannerMgmtHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(BannerMgmtHistListRequest $request): AnonymousResourceCollection
    {
        return $this->bannerMgmtHistService->getAll($request->validated());
    }

    /**
     * Get banner history by ID
     *
     * @param int $id
     * @return BannerMgmtHistResource
     * @throws Exception
     */
    public function show(int $id): BannerMgmtHistResource
    {
        return $this->bannerMgmtHistService->findById($id);
    }

    /**
     * Get banner history by banner ID
     *
     * @param int $bannerId
     * @return AnonymousResourceCollection
     */
    public function getByBannerId(int $bannerId): AnonymousResourceCollection
    {
        return $this->bannerMgmtHistService->findByBannerId($bannerId);
    }

    /**
     * Create a new banner history record
     *
     * @param StoreBannerMgmtHistRequest $request
     * @return BannerMgmtHistResource
     * @throws Exception
     */
    public function store(StoreBannerMgmtHistRequest $request): BannerMgmtHistResource
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->bannerMgmtHistService->create($data);
    }
}
