<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\SettingLinkMgmtHist\ListSettingLinkMgmtHistRequest;
use App\Http\Requests\History\Management\SettingLinkMgmtHist\StoreSettingLinkMgmtHistRequest;
use App\Http\Requests\History\Management\SettingLinkMgmtHist\UpdateSettingLinkMgmtHistRequest;
use App\Http\Requests\History\Management\SettingLinkMgmtHist\DeleteSettingLinkMgmtHistRequest;
use App\Services\History\Management\SettingLinkMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingLinkMgmtHistController extends Controller
{
    public function __construct(
        protected SettingLinkMgmtHistService $settingLinkMgmtHist
    )
    {
    }
    
    /**
     * SettingLinkMgmtHist list
     *
     * @param ListSettingLinkMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListSettingLinkMgmtHistRequest $request): JsonResource
    {
        return $this->settingLinkMgmtHist->list($request->all());
    }

    /**
     * Store setting link mgmt hist
     *
     * @param StoreSettingLinkMgmtHistRequest $request
     * @return int
     */
    public function store(StoreSettingLinkMgmtHistRequest $request): int
    {
        return $this->settingLinkMgmtHist->store($request->all());
    }

    /**
     * Update setting link mgmt hist
     *
     * @param UpdateSettingLinkMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSettingLinkMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->settingLinkMgmtHist->update($payload);
    }

    /**
     * Delete setting link mgmt hist
     *
     * @param DeleteSettingLinkMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteSettingLinkMgmtHistRequest $request): void
    {
        $this->settingLinkMgmtHist->delete($request->all());
    }
}
