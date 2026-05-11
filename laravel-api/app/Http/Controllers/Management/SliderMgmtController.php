<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\SliderMgmt\ListSliderMgmtRequest;
use App\Http\Requests\Management\SliderMgmt\StoreSliderMgmtRequest;
use App\Http\Requests\Management\SliderMgmt\UpdateSliderMgmtRequest;
use App\Http\Requests\Management\SliderMgmt\DeleteSliderMgmtRequest;
use App\Services\Management\SliderMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderMgmtController extends Controller
{
    public function __construct(
        protected SliderMgmtService $sliderMgmt
    )
    {
    }
    
    /**
     * SliderMgmt list
     *
     * @param ListSliderMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListSliderMgmtRequest $request): JsonResource
    {
        return $this->sliderMgmt->list($request->all());
    }

    /**
     * Store slider mgmt
     *
     * @param StoreSliderMgmtRequest $request
     * @return int
     */
    public function store(StoreSliderMgmtRequest $request): int
    {
        return $this->sliderMgmt->store($request->all());
    }

    /**
     * Update slider mgmt
     *
     * @param UpdateSliderMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSliderMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->sliderMgmt->update($payload);
    }

    /**
     * Delete slider mgmt
     *
     * @param DeleteSliderMgmtRequest $request
     * @return void
     */
    public function delete(DeleteSliderMgmtRequest $request): void
    {
        $this->sliderMgmt->delete($request->all());
    }
}
