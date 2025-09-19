<?php

namespace App\Services\Master;

use App\Interfaces\Master\FeatureMstInterface;
use App\Http\Resources\Master\FeatureMstResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class FeatureMstService
{
    protected FeatureMstInterface $featureMstRepository;

    /**
     * Constructor
     *
     * @param FeatureMstInterface $featureMstRepository
     */
    public function __construct(FeatureMstInterface $featureMstRepository)
    {
        $this->featureMstRepository = $featureMstRepository;
    }

    /**
     * Get all features with optional filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $records = $this->featureMstRepository->getAll($payload);
        return FeatureMstResource::collection($records);
    }

    /**
     * Get feature by ID
     *
     * @param int $id
     * @return FeatureMstResource
     */
    public function getById(int $id): FeatureMstResource
    {
        try {
            $record = $this->featureMstRepository->getById($id);
            return new FeatureMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Feature not found: ' . $id);
            throw $e;
        }
    }

    /**
     * Create new feature
     *
     * @param array $payload
     * @return FeatureMstResource
     */
    public function create(array $payload): FeatureMstResource
    {
        $record = $this->featureMstRepository->create($payload);
        return new FeatureMstResource($record);
    }

    /**
     * Update feature
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed
    {
        try {
            $record = $this->featureMstRepository->update($payload, $id);
            return new FeatureMstResource($record);
        } catch (ModelNotFoundException $e) {
            Log::error('Feature not found for update: ' . $id);
            throw $e;
        }
    }

    /**
     * Delete feature
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            // Check if the feature is used by any API
            $feature = $this->featureMstRepository->getById($id);

            if ($feature->apiMst()->count() > 0) {
                throw new Exception('Cannot delete feature that is used by APIs');
            }

            return $this->featureMstRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Feature not found for deletion: ' . $id);
            throw $e;
        }
    }
}
