<?php

namespace App\Http\Resources\Master;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstResource extends JsonResource
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
            'language_id' => (int)$this->language_id,
            'original_id' => (int)$this->original_id,
            'value' => (string)$this->value,
            'updated_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->updated_at)),
        ];
    }
}
