<?php

namespace App\Http\Resources\History\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstHistResource extends JsonResource
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
            'translationMstId' => $this->translation_mst_id,
            'languageId' => $this->language_id,
            'originalId' => $this->original_id,
            'value' => $this->value,
            'action' => $this->action,
            'authorId' => $this->author_id,
            'createdAt' => $this->created_at,
            'translation' => $this->whenLoaded('translation'),
            'language' => $this->whenLoaded('language'),
            'originalTranslator' => $this->whenLoaded('originalTranslator')
        ];
    }
}