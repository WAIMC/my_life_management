<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageMstHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'languageMstId' => $this->language_mst_id,
            'abbreviation' => $this->abbreviation,
            'name' => $this->name,
            'isActive' => $this->is_active,
            'action' => $this->action,
            'authorId' => $this->author_id,
            'createdAt' => $this->created_at,
            'language' => $this->whenLoaded('language'),
            'author' => $this->whenLoaded('author')
        ];
    }
}
