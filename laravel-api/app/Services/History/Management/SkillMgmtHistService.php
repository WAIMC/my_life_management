<?php

namespace App\Services\History\Management;

use App\Http\Resources\History\Management\SkillMgmtHistResource;
use App\Interfaces\History\Management\SkillMgmtHistInterface;
use App\Interfaces\Management\SkillMgmtInterface;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class SkillMgmtHistService
{
    use ApiResponse;

    protected SkillMgmtHistInterface $skillMgmtHistRepository;
    protected SkillMgmtInterface $skillMgmtRepository;

    /**
     * SkillMgmtHistService constructor
     *
     * @param SkillMgmtHistInterface $skillMgmtHistRepository
     * @param SkillMgmtInterface $skillMgmtRepository
     */
    public function __construct(
        SkillMgmtHistInterface $skillMgmtHistRepository,
        SkillMgmtInterface $skillMgmtRepository
    ) {
        $this->skillMgmtHistRepository = $skillMgmtHistRepository;
        $this->skillMgmtRepository = $skillMgmtRepository;
    }

    /**
     * Get all skill history records with filtering and pagination
     *
     * @param array $payload
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getAll(array $payload = []): JsonResponse|AnonymousResourceCollection
    {
        $skillHistories = $this->skillMgmtHistRepository->getAll($payload);
        return SkillMgmtHistResource::collection($skillHistories);
    }

    /**
     * Find skill history by ID
     *
     * @param int $id
     * @return JsonResponse|SkillMgmtHistResource
     * @throws Exception
     */
    public function findById(int $id): JsonResponse|SkillMgmtHistResource
    {
        try {
            $skillHistory = $this->skillMgmtHistRepository->findById($id);
            return new SkillMgmtHistResource($skillHistory);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Find skill history records by skill ID
     *
     * @param int $skillId
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function findBySkillId(int $skillId): JsonResponse|AnonymousResourceCollection
    {
        try {
            // Verify skill exists
            $this->skillMgmtRepository->findById($skillId);
            
            $skillHistories = $this->skillMgmtHistRepository->findBySkillId($skillId);
            return SkillMgmtHistResource::collection($skillHistories);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Create a new skill history record
     *
     * @param array $payload
     * @return JsonResponse|SkillMgmtHistResource
     */
    public function create(array $payload): JsonResponse|SkillMgmtHistResource
    {
        try {
            DB::beginTransaction();

            // Verify skill exists if skill_mgmt_id is provided
            if (isset($payload['skill_mgmt_id'])) {
                $this->skillMgmtRepository->findById($payload['skill_mgmt_id']);
            }

            $skillHistory = $this->skillMgmtHistRepository->create($payload);

            DB::commit();
            return new SkillMgmtHistResource($skillHistory);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
