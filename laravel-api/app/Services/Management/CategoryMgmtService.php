<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Interfaces\History\Management\CategoryMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\CategoryMgmtResource;

class CategoryMgmtService extends BaseService
{
    public function __construct(
        protected CategoryMgmtInterface $categoryMgmt,
        protected CategoryMgmtHistInterface $categoryMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->categoryMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'category_mgmt_id';
    }

    /**
     * Get category mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->categoryMgmt->list($payload);

        return CategoryMgmtResource::collection($list);
    }

    /**
     * Store category mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->categoryMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update category mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->categoryMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete category mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->categoryMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->categoryMgmt->executeDelete($payload['ids']);
    }
}
