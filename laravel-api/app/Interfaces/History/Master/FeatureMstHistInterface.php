<?php

namespace App\Interfaces\History\Master;

interface FeatureMstHistInterface
{
    /**
     * Get all feature history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload);

    /**
     * Get feature history record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Get history records by feature ID
     *
     * @param int $featureMstId
     * @param array $payload
     * @return mixed
     */
    public function getByFeatureId(int $featureMstId, array $payload);

    /**
     * Create new feature history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);

    /**
     * Update feature history record
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);

    /**
     * Delete feature history record
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}