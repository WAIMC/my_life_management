<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\SocialMgmtHistInterface;
use App\Http\Resources\History\Management\SocialMgmtHistResource;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;

class SocialMgmtHistService
{
    protected $socialMgmtHistRepository;
    
    /**
     * Constructor
     * 
     * @param SocialMgmtHistInterface $socialMgmtHistRepository
     */
    public function __construct(SocialMgmtHistInterface $socialMgmtHistRepository)
    {
        $this->socialMgmtHistRepository = $socialMgmtHistRepository;
    }
    
    /**
     * Get social history list
     * 
     * @param array $payload
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(array $payload)
    {
        $socialHistList = $this->socialMgmtHistRepository->list($payload);
        
        return SocialMgmtHistResource::collection($socialHistList);
    }
    
    /**
     * Get social history by ID
     * 
     * @param int $id
     * @return SocialMgmtHistResource
     * @throws NotFoundException
     */
    public function getById(int $id)
    {
        $socialHist = $this->socialMgmtHistRepository->getById($id);
        
        if (!$socialHist) {
            throw new NotFoundException('Social history not found');
        }
        
        return new SocialMgmtHistResource($socialHist);
    }
    
    /**
     * Get social history by social mgmt ID
     * 
     * @param int $socialMgmtId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getBySocialMgmtId(int $socialMgmtId)
    {
        $socialHistList = $this->socialMgmtHistRepository->getBySocialMgmtId($socialMgmtId);
        
        return SocialMgmtHistResource::collection($socialHistList);
    }
    
    /**
     * Store new social history
     * 
     * @param array $payload
     * @return SocialMgmtHistResource
     */
    public function store(array $payload)
    {
        $socialHist = $this->socialMgmtHistRepository->store($payload);
        
        return new SocialMgmtHistResource($socialHist);
    }
    
    /**
     * Update social history
     * 
     * @param array $payload
     * @param int $id
     * @return SocialMgmtHistResource
     * @throws NotFoundException
     */
    public function update(array $payload, int $id)
    {
        $socialHist = $this->socialMgmtHistRepository->update($payload, $id);
        
        if (!$socialHist) {
            throw new NotFoundException('Social history not found');
        }
        
        return new SocialMgmtHistResource($socialHist);
    }
    
    /**
     * Delete social history
     * 
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function delete(int $id)
    {
        $deleted = $this->socialMgmtHistRepository->delete($id);
        
        if (!$deleted) {
            throw new NotFoundException('Social history not found');
        }
        
        return true;
    }
}