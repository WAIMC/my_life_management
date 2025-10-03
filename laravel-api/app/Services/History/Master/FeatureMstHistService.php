<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\FeatureMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\FeatureMstHistResource;

class FeatureMstHistService
{
    public function __construct(
        protected FeatureMstHistInterface $featureMstHist
    )
    {
    }

    /**
     * Get feature mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->featureMstHist->list($payload);

        return FeatureMstHistResource::collection($list);
    }

    /**
     * Store feature mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->featureMstHist->executeStore($payload);
    }

    /**
     * Update feature mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->featureMstHist->executeUpdate($payload);
    }

    /**
     * Delete feature mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->featureMstHist->executeDelete($payload['ids']);
    }
}
