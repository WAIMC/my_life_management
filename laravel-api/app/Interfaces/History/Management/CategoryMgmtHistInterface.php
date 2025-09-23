<?php

namespace App\Interfaces\History\Management;

interface CategoryMgmtHistInterface
{
    /**
     * Get all category history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Find category history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Find category history by category ID
     *
     * @param int $categoryId
     * @return mixed
     */
    public function findByCategoryId(int $categoryId): mixed;

    /**
     * Create new category history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;
}
