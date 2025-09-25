<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\FeatureMstHistResource;
use App\Interfaces\History\Master\FeatureMstHistInterface;
use App\Interfaces\Master\FeatureMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMstHistService
{
    /**
     * FeatureMstHistService constructor.
     *
     * @param FeatureMstHistInterface $featureMstHist
     * @param FeatureMstInterface $featureMst
     */
    public function __construct(
        protected FeatureMstHistInterface $featureMstHist,
        protected FeatureMstInterface     $featureMst
    ) {}

    /**
     * Handle find feature list
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
     * Handle store feature history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->featureMstHist->executeStore($payload);
    }

    /**
     * Handle update feature history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->featureMstHist->executeUpdate($payload);
    }

    /**
     * Delete feature history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->featureMstHist->executeDelete($payload['ids']);
    }
}
