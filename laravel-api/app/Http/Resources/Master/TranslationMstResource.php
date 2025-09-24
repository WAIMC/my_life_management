<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstResource extends JsonResource
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
            'languageId' => $this->language_id,
            'originalId' => $this->original_id,
            'value' => $this->value,
            'language' => $this->whenLoaded('language', function () {
                return [
                    'id' => $this->language->id,
                    'name' => $this->language->name,
                    'code' => $this->language->code ?? null,
                ];
            }),
            'originalTranslator' => $this->whenLoaded('originalTranslator', function () {
                return [
                    'id' => $this->originalTranslator->id,
                    'key' => $this->originalTranslator->key ?? null,
                    'value' => $this->originalTranslator->value ?? null,
                ];
            }),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}