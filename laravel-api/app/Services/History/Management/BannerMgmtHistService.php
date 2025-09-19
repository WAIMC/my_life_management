<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Http\Resources\History\Management\BannerMgmtHistResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BannerMgmtHistService
{
    protected BannerMgmtHistInterface $bannerMgmtHistRepository;

    /**
     * BannerMgmtHistService constructor
     *
     * @param BannerMgmtHistInterface $bannerMgmtHistRepository
     */
    public function __construct(BannerMgmtHistInterface $bannerMgmtHistRepository)
    {
        $this->bannerMgmtHistRepository = $bannerMgmtHistRepository;
    }

    /**
     * Get all banner history records with filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $bannerHistories = $this->bannerMgmtHistRepository->getAll($payload);
        return BannerMgmtHistResource::collection($bannerHistories);
    }

    /**
     * Get banner history by ID
     *
     * @param int $id
     * @return BannerMgmtHistResource
     * @throws Exception
     */
    public function findById(int $id): BannerMgmtHistResource
    {
        $bannerHistory = $this->bannerMgmtHistRepository->findById($id);

        if (!$bannerHistory) {
            throw new Exception("Banner history record not found", 404);
        }

        return new BannerMgmtHistResource($bannerHistory);
    }

    /**
     * Get banner history by banner ID
     *
     * @param int $bannerId
     * @return AnonymousResourceCollection
     */
    public function findByBannerId(int $bannerId): AnonymousResourceCollection
    {
        $bannerHistories = $this->bannerMgmtHistRepository->findByBannerId($bannerId);
        return BannerMgmtHistResource::collection($bannerHistories);
    }

    /**
     * Create new banner history record
     *
     * @param array $payload
     * @return BannerMgmtHistResource
     * @throws Exception
     */
    public function create(array $payload): BannerMgmtHistResource
    {
        try {
            DB::beginTransaction();

            // Validate banner exists
            if (!DB::table('banner_mgmt')->where('id', $payload['banner_mgmt_id'])->exists()) {
                throw new Exception("Banner not found", 404);
            }

            $bannerHistory = $this->bannerMgmtHistRepository->create($payload);

            DB::commit();
            return new BannerMgmtHistResource($bannerHistory);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
