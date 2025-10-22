<?php

namespace App\Http\Resources\History\Master;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginalTranslatorMstHistResource extends JsonResource
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
            'original_translator_mst_id' => (int)$this->original_translator_mst_id,
            'table' => (string)$this->table,
            'column' => (string)$this->column,
            'field_id' => (int)$this->field_id,
            'action' => (string)$this->action,
            'author_id' => (int)$this->author_id,
            'created_at' => (string)date(CommonVal::DATE_FORMAT, strtotime($this->created_at)),
        ];
    }
}
