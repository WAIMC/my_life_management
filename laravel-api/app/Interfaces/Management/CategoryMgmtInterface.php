<?php

namespace App\Interfaces\Management;

interface CategoryMgmtInterface
{
    /**
     * Get all categories with pagination and filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Find category by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Create new category
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update category by ID
     *
     * @param int $id
     * @param array $payload
     * @return mixed
     */
    public function update(int $id, array $payload): mixed;

    /**
     * Delete category by ID
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;

    /**
     * Get categories by parent ID
     *
     * @param int $parentId
     * @param array $payload
     * @return mixed
     */
    public function getByParentId(int $parentId, array $payload): mixed;

    /**
     * Get categories that have no parent (root categories)
     *
     * @param array $payload
     * @return mixed
     */
    public function getRootCategories(array $payload): mixed;
}
