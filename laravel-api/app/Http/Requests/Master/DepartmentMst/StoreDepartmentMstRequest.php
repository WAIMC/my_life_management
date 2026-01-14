<?php

namespace App\Http\Requests\Master\DepartmentMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\DepartmentMst;
use App\Enums\DepartmentStatus;
use App\Enums\IsDelete;

class StoreDepartmentMstRequest extends FormRequest
{
  // ...
  public function rules(): array
  {
    return [
      'code' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50', Rule::unique('department_mst', 'code')->where(fn($query) => $query->where('is_delete', IsDelete::FALSE))],
      'name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
      'status' => ['required', new Enum(DepartmentStatus::class),],
      'is_delete' => ['required', new Enum(IsDelete::class),],
    ];
  }

  public function attributes(): array
  {
    return [
      'code' => __('messages.code'),
      'name' => __('messages.name'),
      'status' => __('messages.status'),
      'is_delete' => __('messages.is_delete'),
    ];
  }
}
