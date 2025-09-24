<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\Social\DeleteSocialMgmtHistRequest;
use App\Http\Requests\History\Management\Social\SocialMgmtHistListRequest;
use App\Http\Requests\History\Management\Social\StoreSocialMgmtHistRequest;
use App\Http\Requests\History\Management\Social\UpdateSocialMgmtHistRequest;
use App\Services\History\Management\SocialMgmtHistService;

class SocialMgmtHistController extends Controller
{
    protected $socialMgmtHistService;

    /**
     * Constructor
     *
     * @param SocialMgmtHistService $socialMgmtHistService
     */
    public function __construct(SocialMgmtHistService $socialMgmtHistService)
    {
        $this->socialMgmtHistService = $socialMgmtHistService;
    }

    /**
     * Get social history list
     *
     * @param SocialMgmtHistListRequest $request
     * @return mixed
     */
    public function index(SocialMgmtHistListRequest $request)
    {
        return $this->socialMgmtHistService->list($request->validated());
    }

    /**
     * Get social history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->socialMgmtHistService->getById($id);
    }

    /**
     * Get social history by social mgmt ID
     *
     * @param int $socialMgmtId
     * @return mixed
     */
    public function getBySocialMgmtId(int $socialMgmtId)
    {
        return $this->socialMgmtHistService->getBySocialMgmtId($socialMgmtId);
    }

    /**
     * Store new social history
     *
     * @param StoreSocialMgmtHistRequest $request
     * @return mixed
     */
    public function store(StoreSocialMgmtHistRequest $request)
    {
        return $this->socialMgmtHistService->store($request->validated());
    }

    /**
     * Update social history
     *
     * @param UpdateSocialMgmtHistRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UpdateSocialMgmtHistRequest $request, int $id)
    {
        return $this->socialMgmtHistService->update($request->validated(), $id);
    }

    /**
     * Delete social history
     *
     * @param DeleteSocialMgmtHistRequest $request
     * @param int $id
     * @return mixed
     */
    public function delete(DeleteSocialMgmtHistRequest $request, int $id)
    {
        return $this->socialMgmtHistService->delete($id);
    }
}
