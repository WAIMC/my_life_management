<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\SkillMgmt\ListSkillMgmtRequest;
use App\Http\Requests\Management\SkillMgmt\StoreSkillMgmtRequest;
use App\Http\Requests\Management\SkillMgmt\UpdateSkillMgmtRequest;
use App\Http\Requests\Management\SkillMgmt\DeleteSkillMgmtRequest;
use App\Services\Management\SkillMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillMgmtController extends Controller
{
    public function __construct(
        protected SkillMgmtService $skillMgmt
    )
    {
    }
    
    /**
     * SkillMgmt list
     *
     * @param ListSkillMgmtRequest $request
     * @return JsonResource
     */
    public function list(ListSkillMgmtRequest $request): JsonResource
    {
        return $this->skillMgmt->list($request->all());
    }

    /**
     * Store skill mgmt
     *
     * @param StoreSkillMgmtRequest $request
     * @return int
     */
    public function store(StoreSkillMgmtRequest $request): int
    {
        return $this->skillMgmt->store($request->all());
    }

    /**
     * Update skill mgmt
     *
     * @param UpdateSkillMgmtRequest $request
     * @param string $id
     * @return int
     */
    public function update(UpdateSkillMgmtRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->skillMgmt->update($payload);
    }

    /**
     * Delete skill mgmt
     *
     * @param DeleteSkillMgmtRequest $request
     * @return void
     */
    public function delete(DeleteSkillMgmtRequest $request): void
    {
        $this->skillMgmt->delete($request->all());
    }
}
