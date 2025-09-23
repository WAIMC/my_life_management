<?php

namespace App\Interfaces\Master;

interface LanguageMstInterface
{
    /**
     * Get all languages
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload);

    /**
     * Get language by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create new language
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);

    /**
     * Update language
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);

    /**
     * Delete language
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
