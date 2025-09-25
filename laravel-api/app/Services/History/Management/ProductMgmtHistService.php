<?php

namespace App\Services\History\Management;

use App\Http\Resources\History\Management\ProductMgmtHistResource;
use App\Interfaces\History\Management\ProductMgmtHistInterface;
use App\Interfaces\Management\ProductMgmtInterface;
use App\Interfaces\Management\CategoryMgmtInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMgmtHistService
{
    /**
     * ProductMgmtHistService constructor.
     *
     * @param ProductMgmtHistInterface $productMgmtHist
     * @param ProductMgmtInterface $productMgmt
     * @param CategoryMgmtInterface $categoryMgmt
     */
    public function __construct(
        protected ProductMgmtHistInterface $productMgmtHist,
        protected ProductMgmtInterface $productMgmt,
        protected CategoryMgmtInterface $categoryMgmt
    ) {}

    /**
     * Handle find product list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->productMgmtHist->list($payload);

        return ProductMgmtHistResource::collection($list);
    }

    /**
     * Handle store product history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->productMgmtHist->executeStore($payload);
    }

    /**
     * Handle update product history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->productMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete product history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->productMgmtHist->executeDelete($payload['ids']);
    }
}
