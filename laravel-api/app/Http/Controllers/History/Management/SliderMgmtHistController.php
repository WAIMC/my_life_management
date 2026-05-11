<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\SliderMgmtHist\ListSliderMgmtHistRequest;
use App\Http\Requests\History\Management\SliderMgmtHist\StoreSliderMgmtHistRequest;
use App\Http\Requests\History\Management\SliderMgmtHist\UpdateSliderMgmtHistRequest;
use App\Http\Requests\History\Management\SliderMgmtHist\DeleteSliderMgmtHistRequest;
use App\Services\History\Management\SliderMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderMgmtHistController extends Controller
{
    public function __construct(
        protected SliderMgmtHistService $sliderMgmtHist
    )
    {
    }
    
    /**
     * SliderMgmtHist list
     *
     * @param ListSliderMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListSliderMgmtHistRequest $request): JsonResource
    {
        return $this->sliderMgmtHist->list($request->all());
    }

    /**
     * Store slider mgmt hist
     *
     * @param StoreSliderMgmtHistRequest $request
     * @return int
     */
    public function store(StoreSliderMgmtHistRequest $request): int
    {
        return $this->sliderMgmtHist->store($request->all());
    }

    /**
     * Update slider mgmt hist
     *
     * @param UpdateSliderMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSliderMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->sliderMgmtHist->update($payload);
    }

    /**
     * Delete slider mgmt hist
     *
     * @param DeleteSliderMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteSliderMgmtHistRequest $request): void
    {
        $this->sliderMgmtHist->delete($request->all());
    }
}
