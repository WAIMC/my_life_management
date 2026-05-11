<?php

namespace App\Http\Requests\Master\AdminMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\AdminMst;
use App\Enums\Gender;
use App\Enums\IsActive;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;

class DeleteAdminMstRequest extends FormRequest
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
      'ids' => ['required', 'array'],
      'ids.*' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_BIG_INTEGER, Rule::exists(AdminMst::class, 'id')],
    ];
  }

  public function attributes(): array
  {
    return [
      'ids' => __('messages.ids'),
      'ids.*' => __('messages.ids'),
    ];
  }
}
