<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Requests\History\Master\FeatureMstHist\ListFeatureMstHistRequest;
use App\Http\Requests\History\Master\FeatureMstHist\StoreFeatureMstHistRequest;
use App\Http\Requests\History\Master\FeatureMstHist\UpdateFeatureMstHistRequest;
use App\Http\Requests\History\Master\FeatureMstHist\DeleteFeatureMstHistRequest;
use App\Services\History\Master\FeatureMstHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMstHistController extends Controller
{
    public function __construct(
        protected FeatureMstHistService $featureMstHist
    )
    {
    }
    
    /**
     * FeatureMstHist list
     *
     * @param ListFeatureMstHistRequest $request
     * @return JsonResource
     */
    public function list(ListFeatureMstHistRequest $request): JsonResource
    {
        return $this->featureMstHist->list($request->all());
    }

    /**
     * Store feature mst hist
     *
     * @param StoreFeatureMstHistRequest $request
     * @return int
     */
    public function store(StoreFeatureMstHistRequest $request): int
    {
        return $this->featureMstHist->store($request->all());
    }

    /**
     * Update feature mst hist
     *
     * @param UpdateFeatureMstHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateFeatureMstHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->featureMstHist->update($payload);
    }

    /**
     * Delete feature mst hist
     *
     * @param DeleteFeatureMstHistRequest $request
     * @return void
     */
    public function delete(DeleteFeatureMstHistRequest $request): void
    {
        $this->featureMstHist->delete($request->all());
    }
}
