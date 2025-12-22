<?php

namespace App\Http\Requests\Management\CategoryMgmt;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Management\CategoryMgmt;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;
use App\Enums\IsActive;

class StoreCategoryMgmtRequest extends FormRequest
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
      'parent_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
      'name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
      'slug' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
      'description' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:150',],
      'status' => ['required', new Enum(StatusEnum::class),],
      'is_display' => ['required', new Enum(IsActive::class),],
      'rank_order' => ['required',],
      'is_delete' => ['required', new Enum(IsDelete::class),],
    ];
  }

  public function attributes(): array
  {
    return [
      'parent_id' => __('messages.parent_id'),
      'name' => __('messages.name'),
      'slug' => __('messages.slug'),
      'description' => __('messages.description'),
      'status' => __('messages.status'),
      'is_display' => __('messages.is_display'),
      'rank_order' => __('messages.rank_order'),
      'is_delete' => __('messages.is_delete'),
    ];
  }
}
