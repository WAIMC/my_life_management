<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\FeatureMstHistResource;
use App\Interfaces\History\Master\FeatureMstHistInterface;
use App\Interfaces\Master\FeatureMstInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeatureMstHistService
{
    /**
     * @var FeatureMstHistInterface
     */
    protected FeatureMstHistInterface $featureMstHistRepository;

    /**
     * @var FeatureMstInterface
     */
    protected FeatureMstInterface $featureMstRepository;

    /**
     * FeatureMstHistService constructor.
     *
     * @param FeatureMstHistInterface $featureMstHistRepository
     * @param FeatureMstInterface $featureMstRepository
     */
    public function __construct(
        FeatureMstHistInterface $featureMstHistRepository,
        FeatureMstInterface     $featureMstRepository
    )
    {
        $this->featureMstHistRepository = $featureMstHistRepository;
        $this->featureMstRepository = $featureMstRepository;
    }

    /**
     * Get all feature history records
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $result = $this->featureMstHistRepository->getAll($payload);
        return FeatureMstHistResource::collection($result);
    }

    /**
     * Get feature history record by ID
     *
     * @param int $id
     * @return FeatureMstHistResource
     * @throws NotFoundHttpException
     */
    public function getById(int $id): FeatureMstHistResource
    {
        $featureMstHist = $this->featureMstHistRepository->getById($id);

        if (!$featureMstHist) {
            throw new NotFoundHttpException('Feature history record not found');
        }

        return new FeatureMstHistResource($featureMstHist);
    }

    /**
     * Get history records by feature ID
     *
     * @param int $featureMstId
     * @param array $payload
     * @return AnonymousResourceCollection
     * @throws NotFoundHttpException
     */
    public function getByFeatureId(int $featureMstId, array $payload): AnonymousResourceCollection
    {
        $feature = $this->featureMstRepository->getById($featureMstId);

        if (!$feature) {
            throw new NotFoundHttpException('Feature not found');
        }

        $result = $this->featureMstHistRepository->getByFeatureId($featureMstId, $payload);
        return FeatureMstHistResource::collection($result);
    }

    /**
     * Create new feature history record
     *
     * @param array $payload
     * @return FeatureMstHistResource
     * @throws NotFoundHttpException
     */
    public function create(array $payload): FeatureMstHistResource
    {
        if (isset($payload['feature_mst_id'])) {
            $feature = $this->featureMstRepository->getById($payload['feature_mst_id']);

            if (!$feature) {
                throw new NotFoundHttpException('Feature not found');
            }
        }

        // Set author_id to current authenticated user if not provided
        if (!isset($payload['author_id']) && Auth::check()) {
            $payload['author_id'] = Auth::id();
        }

        $featureMstHist = $this->featureMstHistRepository->create($payload);
        return new FeatureMstHistResource($featureMstHist);
    }

    /**
     * Update feature history record
     *
     * @param array $payload
     * @param int $id
     * @return FeatureMstHistResource
     * @throws NotFoundHttpException
     */
    public function update(array $payload, int $id): FeatureMstHistResource
    {
        $featureMstHist = $this->featureMstHistRepository->getById($id);

        if (!$featureMstHist) {
            throw new NotFoundHttpException('Feature history record not found');
        }

        if (isset($payload['feature_mst_id'])) {
            $feature = $this->featureMstRepository->getById($payload['feature_mst_id']);

            if (!$feature) {
                throw new NotFoundHttpException('Feature not found');
            }
        }

        $updatedFeatureMstHist = $this->featureMstHistRepository->update($payload, $id);
        return new FeatureMstHistResource($updatedFeatureMstHist);
    }

    /**
     * Delete feature history record
     *
     * @param int $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function delete(int $id): bool
    {
        $featureMstHist = $this->featureMstHistRepository->getById($id);

        if (!$featureMstHist) {
            throw new NotFoundHttpException('Feature history record not found');
        }

        return $this->featureMstHistRepository->delete($id);
    }
}
