<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Requests\History\Management\SkillDescriptionMgmtHist\ListSkillDescriptionMgmtHistRequest;
use App\Http\Requests\History\Management\SkillDescriptionMgmtHist\StoreSkillDescriptionMgmtHistRequest;
use App\Http\Requests\History\Management\SkillDescriptionMgmtHist\UpdateSkillDescriptionMgmtHistRequest;
use App\Http\Requests\History\Management\SkillDescriptionMgmtHist\DeleteSkillDescriptionMgmtHistRequest;
use App\Services\History\Management\SkillDescriptionMgmtHistService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillDescriptionMgmtHistController extends Controller
{
    public function __construct(
        protected SkillDescriptionMgmtHistService $skillDescriptionMgmtHist
    )
    {
    }
    
    /**
     * SkillDescriptionMgmtHist list
     *
     * @param ListSkillDescriptionMgmtHistRequest $request
     * @return JsonResource
     */
    public function list(ListSkillDescriptionMgmtHistRequest $request): JsonResource
    {
        return $this->skillDescriptionMgmtHist->list($request->all());
    }

    /**
     * Store skill description mgmt hist
     *
     * @param StoreSkillDescriptionMgmtHistRequest $request
     * @return int
     */
    public function store(StoreSkillDescriptionMgmtHistRequest $request): int
    {
        return $this->skillDescriptionMgmtHist->store($request->all());
    }

    /**
     * Update skill description mgmt hist
     *
     * @param UpdateSkillDescriptionMgmtHistRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSkillDescriptionMgmtHistRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->skillDescriptionMgmtHist->update($payload);
    }

    /**
     * Delete skill description mgmt hist
     *
     * @param DeleteSkillDescriptionMgmtHistRequest $request
     * @return void
     */
    public function delete(DeleteSkillDescriptionMgmtHistRequest $request): void
    {
        $this->skillDescriptionMgmtHist->delete($request->all());
    }
}
