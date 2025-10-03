<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\UserMgmtHist\ListUserMgmtHistRequest;
use App\Http\Requests\History\Management\UserMgmtHist\StoreUserMgmtHistRequest;
use App\Http\Requests\History\Management\UserMgmtHist\UpdateUserMgmtHistRequest;
use App\Http\Requests\History\Management\UserMgmtHist\DeleteUserMgmtHistRequest;
use App\Services\History\Management\UserMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMgmtHistController extends Controller
{
    public function __construct(
        protected UserMgmtHistService $userMgmtHist
    )
    {
    }
    
    /**
     * UserMgmtHist list
     *
     * @param ListUserMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListUserMgmtHistRequest $request): JsonResource
    {
        return $this->userMgmtHist->list($request->all());
    }

    /**
     * Store user mgmt hist
     *
     * @param StoreUserMgmtHistRequest $request
     * @return int
     */
    public function store(StoreUserMgmtHistRequest $request): int
    {
        return $this->userMgmtHist->store($request->all());
    }

    /**
     * Update user mgmt hist
     *
     * @param UpdateUserMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateUserMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->userMgmtHist->update($payload);
    }

    /**
     * Delete user mgmt hist
     *
     * @param DeleteUserMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteUserMgmtHistRequest $request): void
    {
        $this->userMgmtHist->delete($request->all());
    }
}
