<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Http\Resources\History\Master\PolicyDepartmentMstHistResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PolicyDepartmentMstHistService
{
    /**
     * @var PolicyDepartmentMstHistInterface
     */
    protected PolicyDepartmentMstHistInterface $policyDepartmentMstHistRepository;

    /**
     * PolicyDepartmentMstHistService constructor.
     *
     * @param PolicyDepartmentMstHistInterface $policyDepartmentMstHistRepository
     */
    public function __construct(PolicyDepartmentMstHistInterface $policyDepartmentMstHistRepository)
    {
        $this->policyDepartmentMstHistRepository = $policyDepartmentMstHistRepository;
    }

    /**
     * Get all history records.
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->policyDepartmentMstHistRepository->getAll($payload);
        return PolicyDepartmentMstHistResource::collection($records);
    }

    /**
     * Get history record by ID.
     *
     * @param int $id
     * @return PolicyDepartmentMstHistResource
     */
    public function getById(int $id): PolicyDepartmentMstHistResource
    {
        try {
            $record = $this->policyDepartmentMstHistRepository->getById($id);
            return new PolicyDepartmentMstHistResource($record);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('History record not found');
        }
    }

    /**
     * Create new history record.
     *
     * @param array $payload
     * @return PolicyDepartmentMstHistResource
     * @throws Exception
     */
    public function create(array $payload): PolicyDepartmentMstHistResource
    {
        DB::beginTransaction();
        try {
            $record = $this->policyDepartmentMstHistRepository->create($payload);
            DB::commit();
            return new PolicyDepartmentMstHistResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update history record.
     *
     * @param array $payload
     * @param int $id
     * @return PolicyDepartmentMstHistResource
     * @throws Exception
     */
    public function update(array $payload, int $id): PolicyDepartmentMstHistResource
    {
        DB::beginTransaction();
        try {
            $record = $this->policyDepartmentMstHistRepository->update($payload, $id);
            DB::commit();
            return new PolicyDepartmentMstHistResource($record);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw new ModelNotFoundException('History record not found');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete history record.
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->policyDepartmentMstHistRepository->delete($id);
            DB::commit();
            return $result;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw new ModelNotFoundException('History record not found');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
