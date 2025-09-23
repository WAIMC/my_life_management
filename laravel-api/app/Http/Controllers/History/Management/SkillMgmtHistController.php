<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\Skill\SkillMgmtHistListRequest;
use App\Http\Requests\History\Management\Skill\StoreSkillMgmtHistRequest;
use App\Services\History\Management\SkillMgmtHistService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkillMgmtHistController extends Controller
{
    protected SkillMgmtHistService $skillMgmtHistService;

    /**
     * SkillMgmtHistController constructor
     *
     * @param SkillMgmtHistService $skillMgmtHistService
     */
    public function __construct(SkillMgmtHistService $skillMgmtHistService)
    {
        $this->skillMgmtHistService = $skillMgmtHistService;
    }

    /**
     * Get a listing of skill histories
     *
     * @param SkillMgmtHistListRequest $request
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(SkillMgmtHistListRequest $request): JsonResponse|AnonymousResourceCollection
    {
        return $this->skillMgmtHistService->getAll($request->validated());
    }

    /**
     * Get skill history by ID
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show(int $id): JsonResponse
    {
        return $this->skillMgmtHistService->findById($id);
    }

    /**
     * Get skill history by skill ID
     *
     * @param int $skillId
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getBySkillId(int $skillId): JsonResponse|AnonymousResourceCollection
    {
        return $this->skillMgmtHistService->findBySkillId($skillId);
    }

    /**
     * Create a new skill history record
     *
     * @param StoreSkillMgmtHistRequest $request
     * @return JsonResponse
     */
    public function store(StoreSkillMgmtHistRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_at'] = now()->format('Y-m-d H:i:s');

        return $this->skillMgmtHistService->create($data);
    }
}
