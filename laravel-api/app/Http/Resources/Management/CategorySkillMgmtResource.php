<?php

namespace App\Http\Resources\Management;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySkillMgmtResource extends JsonResource
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
            'category_id' => $this->category_id,
            'skill_id' => $this->skill_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => $this->whenLoaded('category', function() {
                return new CategoryMgmtResource($this->category);
            }),
            'skill' => $this->whenLoaded('skill', function() {
                return new SkillMgmtResource($this->skill);
            })
        ];
    }
}
