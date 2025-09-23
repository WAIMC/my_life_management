<?php

namespace App\Interfaces\Management;

interface CategorySkillMgmtInterface
{
    /**
     * Get all category-skill relationships
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get skills by category ID
     *
     * @param int $categoryId
     * @return mixed
     */
    public function getSkillsByCategoryId(int $categoryId): mixed;

    /**
     * Get categories by skill ID
     *
     * @param int $skillId
     * @return mixed
     */
    public function getCategoriesBySkillId(int $skillId): mixed;

    /**
     * Attach a skill to a category
     *
     * @param int $categoryId
     * @param int $skillId
     * @return mixed
     */
    public function attachSkill(int $categoryId, int $skillId): mixed;

    /**
     * Detach a skill from a category
     *
     * @param int $categoryId
     * @param int $skillId
     * @return mixed
     */
    public function detachSkill(int $categoryId, int $skillId): mixed;

    /**
     * Sync skills for a category
     *
     * @param int $categoryId
     * @param array $skillIds
     * @return mixed
     */
    public function syncSkills(int $categoryId, array $skillIds): mixed;

    /**
     * Check if a relationship exists
     *
     * @param int $categoryId
     * @param int $skillId
     * @return bool
     */
    public function exists(int $categoryId, int $skillId): bool;
}
