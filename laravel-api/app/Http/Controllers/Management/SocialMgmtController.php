<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\SocialMgmt\ListSocialMgmtRequest;
use App\Http\Requests\Management\SocialMgmt\StoreSocialMgmtRequest;
use App\Http\Requests\Management\SocialMgmt\UpdateSocialMgmtRequest;
use App\Http\Requests\Management\SocialMgmt\DeleteSocialMgmtRequest;
use App\Services\Management\SocialMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMgmtController extends Controller
{
    public function __construct(
        protected SocialMgmtService $socialMgmt
    )
    {
    }
    
    /**
     * SocialMgmt list
     *
     * @param ListSocialMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListSocialMgmtRequest $request): JsonResource
    {
        return $this->socialMgmt->list($request->all());
    }

    /**
     * Store social mgmt
     *
     * @param StoreSocialMgmtRequest $request
     * @return int
     */
    public function store(StoreSocialMgmtRequest $request): int
    {
        return $this->socialMgmt->store($request->all());
    }

    /**
     * Update social mgmt
     *
     * @param UpdateSocialMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSocialMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->socialMgmt->update($payload);
    }

    /**
     * Delete social mgmt
     *
     * @param DeleteSocialMgmtRequest $request
     * @return void
     */
    public function delete(DeleteSocialMgmtRequest $request): void
    {
        $this->socialMgmt->delete($request->all());
    }
}
