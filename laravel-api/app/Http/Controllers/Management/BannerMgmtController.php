<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\BannerMgmt\ListBannerMgmtRequest;
use App\Http\Requests\Management\BannerMgmt\StoreBannerMgmtRequest;
use App\Http\Requests\Management\BannerMgmt\UpdateBannerMgmtRequest;
use App\Http\Requests\Management\BannerMgmt\DeleteBannerMgmtRequest;
use App\Services\Management\BannerMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerMgmtController extends Controller
{
    public function __construct(
        protected BannerMgmtService $bannerMgmt
    )
    {
    }
    
    /**
     * BannerMgmt list
     *
     * @param ListBannerMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListBannerMgmtRequest $request): JsonResource
    {
        return $this->bannerMgmt->list($request->all());
    }

    /**
     * Store banner mgmt
     *
     * @param StoreBannerMgmtRequest $request
     * @return int
     */
    public function store(StoreBannerMgmtRequest $request): int
    {
        return $this->bannerMgmt->store($request->all());
    }

    /**
     * Update banner mgmt
     *
     * @param UpdateBannerMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateBannerMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->bannerMgmt->update($payload);
    }

    /**
     * Delete banner mgmt
     *
     * @param DeleteBannerMgmtRequest $request
     * @return void
     */
    public function delete(DeleteBannerMgmtRequest $request): void
    {
        $this->bannerMgmt->delete($request->all());
    }
}
