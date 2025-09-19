<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\AdminMstHistInterface;
use App\Interfaces\Master\AdminMstInterface;
use App\Http\Resources\History\Master\AdminMstHistResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AdminMstHistService
{
    protected AdminMstHistInterface $adminMstHistRepository;
    protected AdminMstInterface $adminMstRepository;

    /**
     * Constructor
     *
     * @param AdminMstHistInterface $adminMstHistRepository
     * @param AdminMstInterface $adminMstRepository
     */
    public function __construct(
        AdminMstHistInterface $adminMstHistRepository,
        AdminMstInterface     $adminMstRepository
    )
    {
        $this->adminMstHistRepository = $adminMstHistRepository;
        $this->adminMstRepository = $adminMstRepository;
    }

    /**
     * Get all admin history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed
    {
        $records = $this->adminMstHistRepository->getAll($payload);
        return AdminMstHistResource::collection($records);
    }

    /**
     * Get admin history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        try {
            $record = $this->adminMstHistRepository->getById($id);
            return new AdminMstHistResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Admin history record not found: ' . $id);
            throw $e;
        }
    }

    /**
     * Create new admin history record
     *
     * @param array $payload
     * @return mixed
     * @throws Exception
     */
    public function create(array $payload): mixed
    {
        // Validate admin_mst_id exists
        if (isset($payload['admin_mst_id'])) {
            try {
                $this->adminMstRepository->getById($payload['admin_mst_id']);
            } catch (ModelNotFoundException $e) {
                Log::error('Referenced admin_mst not found: ' . $payload['admin_mst_id']);
                throw new Exception('Referenced admin does not exist');
            }
        }

        $record = $this->adminMstHistRepository->create($payload);
        return new AdminMstHistResource($record);
    }

    /**
     * Update admin history record
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update(array $payload, int $id): mixed
    {
        try {
            // Validate admin_mst_id exists if it's being updated
            if (isset($payload['admin_mst_id'])) {
                try {
                    $this->adminMstRepository->getById($payload['admin_mst_id']);
                } catch (ModelNotFoundException $e) {
                    Log::error('Referenced admin_mst not found: ' . $payload['admin_mst_id']);
                    throw new Exception('Referenced admin does not exist');
                }
            }

            $record = $this->adminMstHistRepository->update($payload, $id);
            return new AdminMstHistResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Admin history record not found for update: ' . $id);
            throw $e;
        }
    }

    /**
     * Delete admin history record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            return $this->adminMstHistRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Admin history record not found for deletion: ' . $id);
            throw $e;
        }
    }
}
