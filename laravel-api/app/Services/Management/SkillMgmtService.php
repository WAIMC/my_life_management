<?php

namespace App\Services\Management;

use App\Http\Resources\Management\SkillMgmtResource;
use App\Interfaces\Management\SkillMgmtInterface;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class SkillMgmtService
{
    protected SkillMgmtInterface $skillMgmtRepository;

    /**
     * SkillMgmtService constructor
     *
     * @param SkillMgmtInterface $skillMgmtRepository
     */
    public function __construct(SkillMgmtInterface $skillMgmtRepository)
    {
        $this->skillMgmtRepository = $skillMgmtRepository;
    }

    /**
     * Get all skills with pagination and filtering
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getAll(array $payload): AnonymousResourceCollection
    {
        $skills = $this->skillMgmtRepository->getAll($payload);
        return SkillMgmtResource::collection($skills);
    }

    /**
     * Get skill by ID
     *
     * @param int $id
     * @return SkillMgmtResource|null
     * @throws Exception
     */
    public function getById(int $id): ?SkillMgmtResource
    {
        $skill = $this->skillMgmtRepository->findById($id);

        if (!$skill) {
            throw new Exception('Skill not found', 404);
        }

        return new SkillMgmtResource($skill);
    }

    /**
     * Create new skill
     *
     * @param array $payload
     * @return SkillMgmtResource
     * @throws Exception
     */
    public function create(array $payload): SkillMgmtResource
    {
        DB::beginTransaction();
        try {
            // Check if parent_id exists
            if (isset($payload['parent_id']) && $payload['parent_id'] > 0) {
                $parent = $this->skillMgmtRepository->findById($payload['parent_id']);
                if (!$parent) {
                    throw new Exception('Parent skill not found', 404);
                }
            }

            $skill = $this->skillMgmtRepository->create($payload);

            // Create history record would go here if needed

            DB::commit();
            return new SkillMgmtResource($skill);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update skill by ID
     *
     * @param int $id
     * @param array $payload
     * @return SkillMgmtResource
     * @throws Exception
     */
    public function update(int $id, array $payload): SkillMgmtResource
    {
        DB::beginTransaction();
        try {
            // Check if skill exists
            if (!$this->skillMgmtRepository->findById($id)) {
                throw new Exception('Skill not found', 404);
            }

            // Check if parent_id exists and avoid circular reference
            if (isset($payload['parent_id']) && $payload['parent_id'] > 0) {
                if ($payload['parent_id'] == $id) {
                    throw new Exception('A skill cannot be its own parent', 400);
                }

                $parent = $this->skillMgmtRepository->findById($payload['parent_id']);
                if (!$parent) {
                    throw new Exception('Parent skill not found', 404);
                }

                // Check for circular references in hierarchy
                $parentId = $parent->parent_id;
                while ($parentId) {
                    if ($parentId == $id) {
                        throw new Exception('Circular reference detected in skill hierarchy', 400);
                    }
                    $grandparent = $this->skillMgmtRepository->findById($parentId);
                    $parentId = $grandparent ? $grandparent->parent_id : null;
                }
            }

            $skill = $this->skillMgmtRepository->update($id, $payload);

            if (!$skill) {
                throw new Exception('Failed to update skill', 500);
            }

            // Create history record would go here if needed

            DB::commit();
            return new SkillMgmtResource($skill);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete skill by ID
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            // Check if skill exists
            $skill = $this->skillMgmtRepository->findById($id);

            if (!$skill) {
                throw new Exception('Skill not found', 404);
            }

            // Create history record would go here if needed

            $result = $this->skillMgmtRepository->delete($id);

            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
