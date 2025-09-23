<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\Feature\DeleteFeatureMstHistRequest;
use App\Http\Requests\History\Master\Feature\FeatureMstHistListRequest;
use App\Http\Requests\History\Master\Feature\StoreFeatureMstHistRequest;
use App\Http\Requests\History\Master\Feature\UpdateFeatureMstHistRequest;
use App\Http\Resources\History\Master\FeatureMstHistResource;
use App\Services\History\Master\FeatureMstHistService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeatureMstHistController extends Controller
{
    protected FeatureMstHistService $departmentMstHistService;

    /**
     * Constructor
     *
     * @param FeatureMstHistService $departmentMstHistService
     */
    public function __construct(FeatureMstHistService $departmentMstHistService)
    {
        $this->departmentMstHistService = $departmentMstHistService;
    }

    /**
     * Get a listing of feature history records
     *
     * @param FeatureMstHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(FeatureMstHistListRequest $request): AnonymousResourceCollection
    {
        return $this->departmentMstHistService->getAll($request->validated());
    }

    /**
     * Get feature history by ID
     *
     * @param string $id
     * @return FeatureMstHistResource
     */
    public function show(string $id): FeatureMstHistResource
    {
        return $this->departmentMstHistService->getById((int)$id);
    }

    /**
     * Create a new feature history record
     *
     * @param StoreFeatureMstHistRequest $request
     * @return FeatureMstHistResource
     */
    public function store(StoreFeatureMstHistRequest $request): FeatureMstHistResource
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->departmentMstHistService->create($data);
    }

    /**
     * Update a new feature history record
     *
     * @param UpdateFeatureMstHistRequest $request
     * @param string $id
     * @return FeatureMstHistResource
     */
    public function update(UpdateFeatureMstHistRequest $request, string $id): FeatureMstHistResource
    {
        return $this->departmentMstHistService->update($request->all(), $id);
    }

    /**
     * Update a new feature history record
     *
     * @param DeleteFeatureMstHistRequest $request
     * @param string $id
     * @return bool
     */
    public function delete(DeleteFeatureMstHistRequest $request, string $id): bool
    {
        return $this->departmentMstHistService->delete((int)$id);
    }

    /**
     * Get feature history by ID
     *
     * @param $request
     * @param int $departmentMstId
     * @return AnonymousResourceCollection
     */
    public function getByFeatureId($request, int $departmentMstId): AnonymousResourceCollection
    {
        return $this->departmentMstHistService->getByFeatureId($departmentMstId, $request->all());
    }
}
