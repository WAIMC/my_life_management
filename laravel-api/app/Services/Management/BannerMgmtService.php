<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\BannerMgmtInterface;
use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\BannerMgmtResource;

class BannerMgmtService extends BaseService
{
    public function __construct(
        protected BannerMgmtInterface $bannerMgmt,
        protected BannerMgmtHistInterface $bannerMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->bannerMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'banner_mgmt_id';
    }

    /**
     * Get banner mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->bannerMgmt->list($payload);

        return BannerMgmtResource::collection($list);
    }

    /**
     * Store banner mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->bannerMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update banner mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->bannerMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete banner mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->bannerMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->bannerMgmt->executeDelete($payload['ids']);
    }
}
