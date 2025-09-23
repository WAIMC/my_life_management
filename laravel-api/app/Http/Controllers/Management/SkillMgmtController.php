<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Skill\DeleteSkillMgmtRequest;
use App\Http\Requests\Management\Skill\SkillMgmtListRequest;
use App\Http\Requests\Management\Skill\StoreSkillMgmtRequest;
use App\Http\Requests\Management\Skill\UpdateSkillMgmtRequest;
use App\Http\Resources\Management\SkillMgmtResource;
use App\Services\Management\SkillMgmtService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkillMgmtController extends Controller
{
    protected SkillMgmtService $skillMgmtService;

    /**
     * SkillMgmtController constructor
     *
     * @param SkillMgmtService $skillMgmtService
     */
    public function __construct(SkillMgmtService $skillMgmtService)
    {
        $this->skillMgmtService = $skillMgmtService;
    }

    /**
     * Get a listing of skills
     *
     * @param SkillMgmtListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(SkillMgmtListRequest $request): AnonymousResourceCollection
    {
        return $this->skillMgmtService->getAll($request->validated());
    }

    /**
     * Get a single skill by ID
     *
     * @param int $id
     * @return SkillMgmtResource
     * @throws Exception
     */
    public function show(int $id): SkillMgmtResource
    {
        return $this->skillMgmtService->getById($id);
    }

    /**
     * Create a new skill
     *
     * @param StoreSkillMgmtRequest $request
     * @return SkillMgmtResource
     * @throws Exception
     */
    public function store(StoreSkillMgmtRequest $request): SkillMgmtResource
    {
        return $this->skillMgmtService->create($request->validated());
    }

    /**
     * Update an existing skill
     *
     * @param UpdateSkillMgmtRequest $request
     * @param int $id
     * @return SkillMgmtResource
     * @throws Exception
     */
    public function update(UpdateSkillMgmtRequest $request, int $id): SkillMgmtResource
    {
        return $this->skillMgmtService->update($id, $request->validated());
    }

    /**
     * Delete a skill
     *
     * @param DeleteSkillMgmtRequest $request
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(DeleteSkillMgmtRequest $request, int $id): bool
    {
        return $this->skillMgmtService->delete($id);
    }
}
