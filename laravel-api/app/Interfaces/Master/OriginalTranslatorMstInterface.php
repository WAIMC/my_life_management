<?php

namespace App\Interfaces\Master;

interface OriginalTranslatorMstInterface
{
    /**
     * Get list of original translators
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload);

    /**
     * Get original translator by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create original translator
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);

    /**
     * Update original translator
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);

    /**
     * Delete original translator
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
