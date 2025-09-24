<?php

namespace App\Http\Resources\Management;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMgmtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'link' => $this->link,
            'image' => $this->image,
            'status' => $this->status,
            'is_display' => $this->is_display,
            'rank_order' => $this->rank_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
