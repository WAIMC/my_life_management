<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\CategoryMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\CategoryMgmtHistResource;

class CategoryMgmtHistService
{
    public function __construct(
        protected CategoryMgmtHistInterface $categoryMgmtHist
    )
    {
    }

    /**
     * Get category mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->categoryMgmtHist->list($payload);

        return CategoryMgmtHistResource::collection($list);
    }

    /**
     * Store category mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->categoryMgmtHist->executeStore($payload);
    }

    /**
     * Update category mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->categoryMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete category mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->categoryMgmtHist->executeDelete($payload['ids']);
    }
}
