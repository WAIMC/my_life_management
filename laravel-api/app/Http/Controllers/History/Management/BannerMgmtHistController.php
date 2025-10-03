<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\BannerMgmtHist\ListBannerMgmtHistRequest;
use App\Http\Requests\History\Management\BannerMgmtHist\StoreBannerMgmtHistRequest;
use App\Http\Requests\History\Management\BannerMgmtHist\UpdateBannerMgmtHistRequest;
use App\Http\Requests\History\Management\BannerMgmtHist\DeleteBannerMgmtHistRequest;
use App\Services\History\Management\BannerMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerMgmtHistController extends Controller
{
    public function __construct(
        protected BannerMgmtHistService $bannerMgmtHist
    )
    {
    }
    
    /**
     * BannerMgmtHist list
     *
     * @param ListBannerMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListBannerMgmtHistRequest $request): JsonResource
    {
        return $this->bannerMgmtHist->list($request->all());
    }

    /**
     * Store banner mgmt hist
     *
     * @param StoreBannerMgmtHistRequest $request
     * @return int
     */
    public function store(StoreBannerMgmtHistRequest $request): int
    {
        return $this->bannerMgmtHist->store($request->all());
    }

    /**
     * Update banner mgmt hist
     *
     * @param UpdateBannerMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateBannerMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->bannerMgmtHist->update($payload);
    }

    /**
     * Delete banner mgmt hist
     *
     * @param DeleteBannerMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteBannerMgmtHistRequest $request): void
    {
        $this->bannerMgmtHist->delete($request->all());
    }
}
