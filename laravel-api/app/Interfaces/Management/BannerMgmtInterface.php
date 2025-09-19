<?php

namespace App\Interfaces\Management;

interface BannerMgmtInterface
{
    /**
     * Get all banners with pagination and filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Find banner by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Create new banner
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update banner by ID
     *
     * @param int $id
     * @param array $payload
     * @return mixed
     */
    public function update(int $id, array $payload): mixed;

    /**
     * Delete banner by ID
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
