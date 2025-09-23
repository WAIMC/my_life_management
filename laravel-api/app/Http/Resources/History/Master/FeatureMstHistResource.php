<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureMstHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'feature_mst_id' => $this->feature_mst_id,
            'name' => $this->name,
            'group_name' => $this->group_name,
            'description' => $this->description,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'action' => $this->action,
            'action_text' => $this->action_text,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'feature' => $this->whenLoaded('feature'),
        ];
    }
}