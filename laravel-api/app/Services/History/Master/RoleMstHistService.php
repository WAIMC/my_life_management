<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\RoleMstHistInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleMstHistService
{
    /**
     * @var RoleMstHistInterface
     */
    protected RoleMstHistInterface $roleHistoryRepository;

    /**
     * RoleMstHistService constructor.
     *
     * @param RoleMstHistInterface $roleHistoryRepository
     */
    public function __construct(RoleMstHistInterface $roleHistoryRepository)
    {
        $this->roleHistoryRepository = $roleHistoryRepository;
    }

    /**
     * Get all role histories with pagination
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAllRoleHistories(array $params = []): LengthAwarePaginator
    {
        return $this->roleHistoryRepository->getAll($params);
    }

    /**
     * Get role history by ID
     *
     * @param int $id
     * @return object|null
     */
    public function getRoleHistoryById(int $id): ?object
    {
        return $this->roleHistoryRepository->findById($id);
    }

    /**
     * Create new role history
     *
     * @param array $data
     * @return object
     * @throws Exception
     */
    public function createRoleHistory(array $data): object
    {
        try {
            DB::beginTransaction();

            $roleHistory = $this->roleHistoryRepository->create($data);

            DB::commit();
            return $roleHistory;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating role history: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get history by role ID
     *
     * @param int $roleId
     * @return Collection
     */
    public function getRoleHistoriesByRoleId(int $roleId): Collection
    {
        return $this->roleHistoryRepository->getByRoleId($roleId);
    }

    /**
     * Get history by author ID
     *
     * @param int $authorId
     * @return Collection
     */
    public function getRoleHistoriesByAuthorId(int $authorId): Collection
    {
        return $this->roleHistoryRepository->getByAuthorId($authorId);
    }

    /**
     * Get history by action type
     *
     * @param int $action
     * @return Collection
     */
    public function getRoleHistoriesByAction(int $action): Collection
    {
        return $this->roleHistoryRepository->getByAction($action);
    }
}
