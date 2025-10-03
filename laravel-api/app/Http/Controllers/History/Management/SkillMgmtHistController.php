<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\SkillMgmtHist\ListSkillMgmtHistRequest;
use App\Http\Requests\History\Management\SkillMgmtHist\StoreSkillMgmtHistRequest;
use App\Http\Requests\History\Management\SkillMgmtHist\UpdateSkillMgmtHistRequest;
use App\Http\Requests\History\Management\SkillMgmtHist\DeleteSkillMgmtHistRequest;
use App\Services\History\Management\SkillMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillMgmtHistController extends Controller
{
    public function __construct(
        protected SkillMgmtHistService $skillMgmtHist
    )
    {
    }
    
    /**
     * SkillMgmtHist list
     *
     * @param ListSkillMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListSkillMgmtHistRequest $request): JsonResource
    {
        return $this->skillMgmtHist->list($request->all());
    }

    /**
     * Store skill mgmt hist
     *
     * @param StoreSkillMgmtHistRequest $request
     * @return int
     */
    public function store(StoreSkillMgmtHistRequest $request): int
    {
        return $this->skillMgmtHist->store($request->all());
    }

    /**
     * Update skill mgmt hist
     *
     * @param UpdateSkillMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSkillMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->skillMgmtHist->update($payload);
    }

    /**
     * Delete skill mgmt hist
     *
     * @param DeleteSkillMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteSkillMgmtHistRequest $request): void
    {
        $this->skillMgmtHist->delete($request->all());
    }
}
