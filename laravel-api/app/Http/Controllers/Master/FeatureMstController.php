<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\FeatureMst\ListFeatureMstRequest;
use App\Http\Requests\Master\FeatureMst\StoreFeatureMstRequest;
use App\Http\Requests\Master\FeatureMst\UpdateFeatureMstRequest;
use App\Http\Requests\Master\FeatureMst\DeleteFeatureMstRequest;
use App\Services\Master\FeatureMstService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMstController extends Controller
{
    public function __construct(
        protected FeatureMstService $featureMst
    )
    {
    }
    
    /**
     * FeatureMst list
     *
     * @param ListFeatureMstRequest $request
     * @return JsonResource
     */
    public function list(ListFeatureMstRequest $request): JsonResource
    {
        return $this->featureMst->list($request->all());
    }

    /**
     * Store feature mst
     *
     * @param StoreFeatureMstRequest $request
     * @return int
     */
    public function store(StoreFeatureMstRequest $request): int
    {
        return $this->featureMst->store($request->all());
    }

    /**
     * Update feature mst
     *
     * @param UpdateFeatureMstRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateFeatureMstRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->featureMst->update($payload);
    }

    /**
     * Delete feature mst
     *
     * @param DeleteFeatureMstRequest $request
     * @return void
     */
    public function delete(DeleteFeatureMstRequest $request): void
    {
        $this->featureMst->delete($request->all());
    }
}
