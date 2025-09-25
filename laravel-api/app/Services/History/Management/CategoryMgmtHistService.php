<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\CategoryMgmtHistInterface;
use App\Http\Resources\History\Management\CategoryMgmtHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMgmtHistService
{
    /**
     * CategoryMgmtHistService constructor
     *
     * @param CategoryMgmtHistInterface $categoryMgmtHist
     */
    public function __construct(protected CategoryMgmtHistInterface $categoryMgmtHist)
    {}

    /**
     * Get all category history records with filtering
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $categoryHistories = $this->categoryMgmtHist->list($payload);

        return CategoryMgmtHistResource::collection($categoryHistories);
    }

    /**
     * Handle store category history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->categoryMgmtHist->executeStore($payload);
    }

    /**
     * Handle update category history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->categoryMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete category history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->categoryMgmtHist->executeDelete($payload['ids']);
    }
}
