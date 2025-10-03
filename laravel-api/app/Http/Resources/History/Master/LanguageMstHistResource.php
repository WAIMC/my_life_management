<?php

namespace App\Http\Resources\History\Master;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageMstHistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'language_mst_id' => (int)$this->language_mst_id,
            'abbreviation' => (string)$this->abbreviation,
            'name' => (string)$this->name,
            'is_active' => (bool)$this->is_active,
            'action' => (string)$this->action,
            'author_id' => (int)$this->author_id,
            'created_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->created_at)),
        ];
    }
}
