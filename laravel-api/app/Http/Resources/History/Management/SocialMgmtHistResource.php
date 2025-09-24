<?php

namespace App\Http\Resources\History\Management;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialMgmtHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'socialMgmtId' => $this->social_mgmt_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'link' => $this->link,
            'image' => $this->image,
            'status' => $this->status,
            'isDisplay' => $this->is_display,
            'rankOrder' => $this->rank_order,
            'action' => $this->action,
            'authorId' => $this->author_id,
            'createdAt' => $this->created_at,
        ];
    }
}