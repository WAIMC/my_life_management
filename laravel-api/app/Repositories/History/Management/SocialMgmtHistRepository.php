<?php

namespace App\Repositories\History\Management;

use App\Models\History\Management\SocialMgmtHist;
use App\Interfaces\History\Management\SocialMgmtHistInterface;
use Illuminate\Database\Eloquent\Collection;

class SocialMgmtHistRepository implements SocialMgmtHistInterface
{
    protected $model;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new SocialMgmtHist();
    }
    
    /**
     * Get social history list with conditions
     * 
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload)
    {
        $query = $this->model->select('*')->orderBy('id');
        
        if (isset($payload['social_mgmt_id'])) {
            $query->where('social_mgmt_id', $payload['social_mgmt_id']);
        }
        
        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }
        
        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }
        
        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }
        
        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }
        
        return $query->get();
    }
    
    /**
     * Get social history by ID
     * 
     * @param int $id
     * @return SocialMgmtHist|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }
    
    /**
     * Get social history by social mgmt ID
     * 
     * @param int $socialMgmtId
     * @return Collection
     */
    public function getBySocialMgmtId(int $socialMgmtId)
    {
        return $this->model->where('social_mgmt_id', $socialMgmtId)
            ->orderBy('id', 'desc')
            ->get();
    }
    
    /**
     * Store new social history
     * 
     * @param array $payload
     * @return SocialMgmtHist
     */
    public function store(array $payload)
    {
        $socialHist = new SocialMgmtHist();
        
        if (isset($payload['social_mgmt_id'])) {
            $socialHist->social_mgmt_id = $payload['social_mgmt_id'];
        }
        
        if (isset($payload['name'])) {
            $socialHist->name = $payload['name'];
        }
        
        if (isset($payload['slug'])) {
            $socialHist->slug = $payload['slug'];
        }
        
        if (isset($payload['link'])) {
            $socialHist->link = $payload['link'];
        }
        
        if (isset($payload['image'])) {
            $socialHist->image = $payload['image'];
        }
        
        if (isset($payload['status'])) {
            $socialHist->status = $payload['status'];
        }
        
        if (isset($payload['is_display'])) {
            $socialHist->is_display = $payload['is_display'];
        }
        
        if (isset($payload['rank_order'])) {
            $socialHist->rank_order = $payload['rank_order'];
        }
        
        $socialHist->action = $payload['action'];
        $socialHist->author_id = $payload['author_id'];
        $socialHist->created_at = now();
        
        $socialHist->save();
        
        return $socialHist;
    }
    
    /**
     * Update social history
     * 
     * @param array $payload
     * @param int $id
     * @return SocialMgmtHist|null
     */
    public function update(array $payload, int $id)
    {
        $socialHist = $this->model->find($id);
        
        if (!$socialHist) {
            return null;
        }
        
        if (isset($payload['social_mgmt_id'])) {
            $socialHist->social_mgmt_id = $payload['social_mgmt_id'];
        }
        
        if (isset($payload['name'])) {
            $socialHist->name = $payload['name'];
        }
        
        if (isset($payload['slug'])) {
            $socialHist->slug = $payload['slug'];
        }
        
        if (isset($payload['link'])) {
            $socialHist->link = $payload['link'];
        }
        
        if (isset($payload['image'])) {
            $socialHist->image = $payload['image'];
        }
        
        if (isset($payload['status'])) {
            $socialHist->status = $payload['status'];
        }
        
        if (isset($payload['is_display'])) {
            $socialHist->is_display = $payload['is_display'];
        }
        
        if (isset($payload['rank_order'])) {
            $socialHist->rank_order = $payload['rank_order'];
        }
        
        if (isset($payload['action'])) {
            $socialHist->action = $payload['action'];
        }
        
        if (isset($payload['author_id'])) {
            $socialHist->author_id = $payload['author_id'];
        }
        
        $socialHist->save();
        
        return $socialHist;
    }
    
    /**
     * Delete social history
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $socialHist = $this->model->find($id);
        
        if (!$socialHist) {
            return false;
        }
        
        return $socialHist->delete();
    }
}