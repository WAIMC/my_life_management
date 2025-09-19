<?php

namespace App\Interfaces\History\Management;

interface BannerMgmtHistInterface
{
    /**
     * Get all banner history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Find banner history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Find banner history by banner ID
     *
     * @param int $bannerId
     * @return mixed
     */
    public function findByBannerId(int $bannerId): mixed;

    /**
     * Create new banner history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;
}
