<?php

namespace App\Interfaces\History\Master;

interface LanguageMstHistInterface
{
    /**
     * Get list of language history
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload);

    /**
     * Get language history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create language history
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);

    /**
     * Update language history
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);

    /**
     * Delete language history
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
