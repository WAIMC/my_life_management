<?php

namespace App\Http\Resources\History\Master;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstHistResource extends JsonResource
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
            'translation_mst_id' => (int)$this->translation_mst_id,
            'language_id' => (int)$this->language_id,
            'original_id' => (int)$this->original_id,
            'value' => (string)$this->value,
            'action' => (int)$this->action,
            'author_id' => (int)$this->author_id,
            'created_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->created_at)),
        ];
    }
}
