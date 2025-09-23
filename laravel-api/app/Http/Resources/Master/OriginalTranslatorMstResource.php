<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class OriginalTranslatorMstResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'table' => $this->table,
            'column' => $this->column,
            'fieldId' => $this->field_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'translations' => $this->whenLoaded('translations'),
            'history' => $this->whenLoaded('history')
        ];
    }
}
