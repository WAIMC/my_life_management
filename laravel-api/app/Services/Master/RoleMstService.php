<?php

namespace App\Services\Master;

use App\Interfaces\Master\RoleMstInterface;
use App\Http\Resources\Master\RoleMstResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class RoleMstService
{
    protected RoleMstInterface $roleMstRepository;

    /**
     * Constructor
     *
     * @param RoleMstInterface $roleMstRepository
     */
    public function __construct(RoleMstInterface $roleMstRepository)
    {
        $this->roleMstRepository = $roleMstRepository;
    }

    /**
     * Get all roles with optional filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->roleMstRepository->getAll($payload);
        return RoleMstResource::collection($records);
    }

    /**
     * Get role by ID
     *
     * @param int $id
     * @return RoleMstResource
     */
    public function getById(int $id): RoleMstResource
    {
        try {
            $record = $this->roleMstRepository->getById($id);
            return new RoleMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Role not found: ' . $id);
            throw $e;
        }
    }

    /**
     * Create new role
     *
     * @param array $payload
     * @return RoleMstResource
     */
    public function create(array $payload): RoleMstResource
    {
        $record = $this->roleMstRepository->create($payload);
        return new RoleMstResource($record);
    }

    /**
     * Update role
     *
     * @param array $payload
     * @param int $id
     * @return RoleMstResource
     */
    public function update(array $payload, int $id): RoleMstResource
    {
        try {
            $record = $this->roleMstRepository->update($payload, $id);
            return new RoleMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Role not found for update: ' . $id);
            throw $e;
        }
    }

    /**
     * Delete role
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            // Check if the role is used by any admin or API
            $role = $this->roleMstRepository->getById($id);

            if ($role->adminRoles()->count() > 0) {
                throw new Exception('Cannot delete role that is assigned to admins');
            }

            if ($role->apiRoles()->count() > 0) {
                throw new Exception('Cannot delete role that is assigned to APIs');
            }

            return $this->roleMstRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Role not found for deletion: ' . $id);
            throw $e;
        }
    }
}
