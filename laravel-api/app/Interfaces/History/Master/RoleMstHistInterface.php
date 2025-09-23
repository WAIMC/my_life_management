<?php

namespace App\Interfaces\History\Master;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoleMstHistInterface
{
    /**
     * Get all role histories with pagination
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll(array $params = []): LengthAwarePaginator;

    /**
     * Get role history by ID
     *
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object;

    /**
     * Create new role history
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object;

    /**
     * Get history by role ID
     *
     * @param int $roleId
     * @return Collection
     */
    public function getByRoleId(int $roleId): Collection;

    /**
     * Get history by author ID
     *
     * @param int $authorId
     * @return Collection
     */
    public function getByAuthorId(int $authorId): Collection;

    /**
     * Get history by action type
     *
     * @param int $action
     * @return Collection
     */
    public function getByAction(int $action): Collection;
}
