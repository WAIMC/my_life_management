<?php

namespace App\Interfaces\History\Master;

interface ApiMstHistInterface
{
    /**
     * Get all API history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get API history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Get API history by API master ID
     *
     * @param int $apiMstId
     * @return mixed
     */
    public function findByApiMstId(int $apiMstId): mixed;

    /**
     * Create new API history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;
}
