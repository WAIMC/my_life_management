<?php

namespace App\Interfaces\Management;

interface MediaFileInterface
{
    /**
     * Get list of media files
     *
     * @param array $payload
     * @return mixed
     */
    public function list(array $payload);

    /**
     * Store media file metadata
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update media file metadata
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete media file records
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;

    /**
     * Find media file by ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Find media file by Google Drive file ID
     *
     * @param string $googleFileId
     * @return mixed
     */
    public function findByGoogleFileId(string $googleFileId);
}
