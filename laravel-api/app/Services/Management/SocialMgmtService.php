<?php

namespace App\Services\Management;

use App\Interfaces\Management\SocialMgmtInterface;
use App\Http\Resources\Management\SocialMgmtResource;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SocialMgmtService
{
    /**
     * @var SocialMgmtInterface
     */
    protected $socialRepository;

    /**
     * SocialMgmtService constructor.
     *
     * @param SocialMgmtInterface $socialRepository
     */
    public function __construct(SocialMgmtInterface $socialRepository)
    {
        $this->socialRepository = $socialRepository;
    }

    /**
     * Get list of socials with pagination
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getList(array $payload): AnonymousResourceCollection
    {
        $socials = $this->socialRepository->getList($payload);
        return SocialMgmtResource::collection($socials);
    }

    /**
     * Get social by ID
     *
     * @param int $id
     * @return SocialMgmtResource|null
     */
    public function getById(int $id): ?SocialMgmtResource
    {
        $social = $this->socialRepository->getById($id);
        
        if (!$social) {
            return null;
        }
        
        return new SocialMgmtResource($social);
    }

    /**
     * Create a new social
     *
     * @param array $payload
     * @return SocialMgmtResource
     */
    public function create(array $payload): SocialMgmtResource
    {
        // Generate slug if not provided
        if (!isset($payload['slug']) || empty($payload['slug'])) {
            $payload['slug'] = Str::slug($payload['name']);
        }

        $social = $this->socialRepository->create($payload);
        return new SocialMgmtResource($social);
    }

    /**
     * Update an existing social
     *
     * @param array $payload
     * @param int $id
     * @return SocialMgmtResource|null
     */
    public function update(array $payload, int $id): ?SocialMgmtResource
    {
        // Check if social exists
        $social = $this->socialRepository->getById($id);
        
        if (!$social) {
            return null;
        }
        
        // Generate slug if name is changed and slug is not provided
        if (isset($payload['name']) && (!isset($payload['slug']) || empty($payload['slug']))) {
            $payload['slug'] = Str::slug($payload['name']);
        }

        $updatedSocial = $this->socialRepository->update($payload, $id);
        
        if (!$updatedSocial) {
            return null;
        }
        
        return new SocialMgmtResource($updatedSocial);
    }

    /**
     * Delete a social
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        // Check if social exists
        $social = $this->socialRepository->getById($id);
        
        if (!$social) {
            return false;
        }
        
        return $this->socialRepository->delete($id);
    }
}
