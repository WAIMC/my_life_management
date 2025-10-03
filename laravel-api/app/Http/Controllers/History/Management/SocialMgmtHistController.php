<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\SocialMgmtHist\ListSocialMgmtHistRequest;
use App\Http\Requests\History\Management\SocialMgmtHist\StoreSocialMgmtHistRequest;
use App\Http\Requests\History\Management\SocialMgmtHist\UpdateSocialMgmtHistRequest;
use App\Http\Requests\History\Management\SocialMgmtHist\DeleteSocialMgmtHistRequest;
use App\Services\History\Management\SocialMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMgmtHistController extends Controller
{
    public function __construct(
        protected SocialMgmtHistService $socialMgmtHist
    )
    {
    }
    
    /**
     * SocialMgmtHist list
     *
     * @param ListSocialMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListSocialMgmtHistRequest $request): JsonResource
    {
        return $this->socialMgmtHist->list($request->all());
    }

    /**
     * Store social mgmt hist
     *
     * @param StoreSocialMgmtHistRequest $request
     * @return int
     */
    public function store(StoreSocialMgmtHistRequest $request): int
    {
        return $this->socialMgmtHist->store($request->all());
    }

    /**
     * Update social mgmt hist
     *
     * @param UpdateSocialMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSocialMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->socialMgmtHist->update($payload);
    }

    /**
     * Delete social mgmt hist
     *
     * @param DeleteSocialMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteSocialMgmtHistRequest $request): void
    {
        $this->socialMgmtHist->delete($request->all());
    }
}
