<?php

namespace App\Http\Requests\Management\EntryMgmt;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Management\EntryMgmt;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;
use App\Enums\IsDisplay;

class UpdateEntryMgmtRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'id' => ['required', 'integer', 'min:1', Rule::exists(EntryMgmt::class, 'id')],
      'parent_id' => ['nullable', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
      'name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
      'slug' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
      'status' => ['required', new Enum(StatusEnum::class),],
      'is_display' => ['required', new Enum(IsDisplay::class),],
      'rank_order' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
      'is_delete' => ['nullable', new Enum(IsDelete::class),],
    ];
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation(): void
  {
    $this->merge([
      'is_display' => $this->boolean('is_display') ? 1 : 0,
      'is_delete' => $this->has('is_delete') ? $this->input('is_delete') : 0,
    ]);
  }

  public function attributes(): array
  {
    return [
      'parent_id' => __('messages.parent_id'),
      'name' => __('messages.name'),
      'slug' => __('messages.slug'),
      'status' => __('messages.status'),
      'is_display' => __('messages.is_display'),
      'rank_order' => __('messages.rank_order'),
      'is_delete' => __('messages.is_delete'),
    ];
  }
}
