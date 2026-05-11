<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\SliderMgmtInterface;
use App\Interfaces\History\Management\SliderMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\SliderMgmtResource;

class SliderMgmtService extends BaseService
{
    public function __construct(
        protected SliderMgmtInterface $sliderMgmt,
        protected SliderMgmtHistInterface $sliderMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->sliderMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'slider_mgmt_id';
    }

    /**
     * Get slider mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->sliderMgmt->list($payload);

        return SliderMgmtResource::collection($list);
    }

    /**
     * Store slider mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->sliderMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update slider mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->sliderMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete slider mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->sliderMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->sliderMgmt->executeDelete($payload['ids']);
    }
}
