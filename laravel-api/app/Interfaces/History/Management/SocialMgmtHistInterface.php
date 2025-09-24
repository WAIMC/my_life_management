<?php

namespace App\Interfaces\History\Management;

interface SocialMgmtHistInterface
{
    /**
     * Get social history list with conditions
     * 
     * @param array $payload
     * @return mixed
     */
    public function list(array $payload);
    
    /**
     * Get social history by ID
     * 
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);
    
    /**
     * Get social history by social mgmt ID
     * 
     * @param int $socialMgmtId
     * @return mixed
     */
    public function getBySocialMgmtId(int $socialMgmtId);
    
    /**
     * Store new social history
     * 
     * @param array $payload
     * @return mixed
     */
    public function store(array $payload);
    
    /**
     * Update social history
     * 
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);
    
    /**
     * Delete social history
     * 
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}