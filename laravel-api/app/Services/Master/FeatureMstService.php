<?php

namespace App\Services\Master;

use App\Interfaces\Master\FeatureMstInterface;
use App\Http\Resources\Master\FeatureMstResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMstService
{
    /**
     * Constructor
     *
     * @param FeatureMstInterface $featureMst
     */
    public function __construct(protected FeatureMstInterface $featureMst) {}

    /**
     * Get all features with optional filtering
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->featureMst->list($payload);

        return FeatureMstResource::collection($list);
    }

    /**
     * Create new feature
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->featureMst->executeStore($payload);
    }

    /**
     * Update feature
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->featureMst->executeUpdate($payload);
    }

    /**
     * Delete feature
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->featureMst->executeDelete($payload['ids']);
    }
}
