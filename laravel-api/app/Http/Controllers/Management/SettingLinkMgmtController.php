<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\SettingLinkMgmt\ListSettingLinkMgmtRequest;
use App\Http\Requests\Management\SettingLinkMgmt\StoreSettingLinkMgmtRequest;
use App\Http\Requests\Management\SettingLinkMgmt\UpdateSettingLinkMgmtRequest;
use App\Http\Requests\Management\SettingLinkMgmt\DeleteSettingLinkMgmtRequest;
use App\Services\Management\SettingLinkMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingLinkMgmtController extends Controller
{
    public function __construct(
        protected SettingLinkMgmtService $settingLinkMgmt
    )
    {
    }
    
    /**
     * SettingLinkMgmt list
     *
     * @param ListSettingLinkMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListSettingLinkMgmtRequest $request): JsonResource
    {
        return $this->settingLinkMgmt->list($request->all());
    }

    /**
     * Store setting link mgmt
     *
     * @param StoreSettingLinkMgmtRequest $request
     * @return int
     */
    public function store(StoreSettingLinkMgmtRequest $request): int
    {
        return $this->settingLinkMgmt->store($request->all());
    }

    /**
     * Update setting link mgmt
     *
     * @param UpdateSettingLinkMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSettingLinkMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->settingLinkMgmt->update($payload);
    }

    /**
     * Delete setting link mgmt
     *
     * @param DeleteSettingLinkMgmtRequest $request
     * @return void
     */
    public function delete(DeleteSettingLinkMgmtRequest $request): void
    {
        $this->settingLinkMgmt->delete($request->all());
    }
}
