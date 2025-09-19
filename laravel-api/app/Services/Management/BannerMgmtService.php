<?php

namespace App\Services\Management;

use App\Enums\ActionType;
use App\Interfaces\Management\BannerMgmtInterface;
use App\Http\Resources\Management\BannerMgmtResource;
use App\Repositories\History\Management\BannerMgmtHistRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BannerMgmtService
{
    protected BannerMgmtInterface $bannerMgmtRepository;
    protected BannerMgmtHistRepository $bannerMgmtHistRepository;

    /**
     * BannerMgmtService constructor
     *
     * @param BannerMgmtInterface $bannerMgmtRepository
     * @param BannerMgmtHistRepository $bannerMgmtHistRepository
     */
    public function __construct(
        BannerMgmtInterface $bannerMgmtRepository,
        BannerMgmtHistRepository $bannerMgmtHistRepository
    ) {
        $this->bannerMgmtRepository = $bannerMgmtRepository;
        $this->bannerMgmtHistRepository = $bannerMgmtHistRepository;
    }

    /**
     * Get all banners with filtering and pagination
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $banners = $this->bannerMgmtRepository->getAll($payload);
        return BannerMgmtResource::collection($banners);
    }

    /**
     * Get banner by ID
     *
     * @param int $id
     * @return BannerMgmtResource
     * @throws Exception
     */
    public function findById(int $id): BannerMgmtResource
    {
        $banner = $this->bannerMgmtRepository->findById($id);

        if (!$banner) {
            throw new Exception("Banner not found", 404);
        }

        return new BannerMgmtResource($banner);
    }

    /**
     * Create new banner
     *
     * @param array $payload
     * @return BannerMgmtResource
     * @throws Exception
     */
    public function create(array $payload): BannerMgmtResource
    {
        try {
            DB::beginTransaction();

            $banner = $this->bannerMgmtRepository->create($payload);

            // Create history record
            if (isset($payload['author_id'])) {
                $historyData = [
                    'banner_mgmt_id' => $banner->id,
                    'title' => $banner->title,
                    'slug' => $banner->slug,
                    'description' => $banner->description,
                    'link' => $banner->link,
                    'image' => $banner->image,
                    'position' => $banner->position,
                    'status' => $banner->status,
                    'action' => ActionType::CREATE,
                    'author_id' => $payload['author_id'],
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];

                $this->bannerMgmtHistRepository->create($historyData);
            }

            DB::commit();
            return new BannerMgmtResource($banner);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update banner by ID
     *
     * @param int $id
     * @param array $payload
     * @return BannerMgmtResource
     * @throws Exception
     */
    public function update(int $id, array $payload): BannerMgmtResource
    {
        try {
            DB::beginTransaction();

            $banner = $this->bannerMgmtRepository->findById($id);

            if (!$banner) {
                throw new Exception("Banner not found", 404);
            }

            $updatedBanner = $this->bannerMgmtRepository->update($id, $payload);

            // Create history record
            if (isset($payload['author_id'])) {
                $historyData = [
                    'banner_mgmt_id' => $updatedBanner->id,
                    'title' => $updatedBanner->title,
                    'slug' => $updatedBanner->slug,
                    'description' => $updatedBanner->description,
                    'link' => $updatedBanner->link,
                    'image' => $updatedBanner->image,
                    'position' => $updatedBanner->position,
                    'status' => $updatedBanner->status,
                    'action' => ActionType::UPDATE,
                    'author_id' => $payload['author_id'],
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];

                $this->bannerMgmtHistRepository->create($historyData);
            }

            DB::commit();
            return new BannerMgmtResource($updatedBanner);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete banner by ID
     *
     * @param int $id
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function delete(int $id, array $payload): array
    {
        try {
            DB::beginTransaction();

            $banner = $this->bannerMgmtRepository->findById($id);

            if (!$banner) {
                throw new Exception("Banner not found", 404);
            }

            // Store banner data for history before deletion
            $bannerData = [
                'title' => $banner->title,
                'slug' => $banner->slug,
                'description' => $banner->description,
                'link' => $banner->link,
                'image' => $banner->image,
                'position' => $banner->position,
                'status' => $banner->status,
            ];

            $this->bannerMgmtRepository->delete($id);

            // Create history record
            if (isset($payload['author_id'])) {
                $historyData = array_merge($bannerData, [
                    'banner_mgmt_id' => $id,
                    'action' => ActionType::DELETE,
                    'author_id' => $payload['author_id'],
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);

                $this->bannerMgmtHistRepository->create($historyData);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Banner deleted successfully'];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
