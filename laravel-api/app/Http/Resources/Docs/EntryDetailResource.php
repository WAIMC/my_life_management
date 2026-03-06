<?php

namespace App\Http\Resources\Docs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'descriptions' => DescriptionResource::collection($this->whenLoaded('descriptions')),
        ];
    }
}
