<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\SkillDescriptionMgmt\ListSkillDescriptionMgmtRequest;
use App\Http\Requests\Management\SkillDescriptionMgmt\StoreSkillDescriptionMgmtRequest;
use App\Http\Requests\Management\SkillDescriptionMgmt\UpdateSkillDescriptionMgmtRequest;
use App\Http\Requests\Management\SkillDescriptionMgmt\DeleteSkillDescriptionMgmtRequest;
use App\Services\Management\SkillDescriptionMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillDescriptionMgmtController extends Controller
{
    public function __construct(
        protected SkillDescriptionMgmtService $skillDescriptionMgmt
    )
    {
    }
    
    /**
     * SkillDescriptionMgmt list
     *
     * @param ListSkillDescriptionMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListSkillDescriptionMgmtRequest $request): JsonResource
    {
        return $this->skillDescriptionMgmt->list($request->all());
    }

    /**
     * Store skill description mgmt
     *
     * @param StoreSkillDescriptionMgmtRequest $request
     * @return int
     */
    public function store(StoreSkillDescriptionMgmtRequest $request): int
    {
        return $this->skillDescriptionMgmt->store($request->all());
    }

    /**
     * Update skill description mgmt
     *
     * @param UpdateSkillDescriptionMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSkillDescriptionMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->skillDescriptionMgmt->update($payload);
    }

    /**
     * Delete skill description mgmt
     *
     * @param DeleteSkillDescriptionMgmtRequest $request
     * @return void
     */
    public function delete(DeleteSkillDescriptionMgmtRequest $request): void
    {
        $this->skillDescriptionMgmt->delete($request->all());
    }
}
