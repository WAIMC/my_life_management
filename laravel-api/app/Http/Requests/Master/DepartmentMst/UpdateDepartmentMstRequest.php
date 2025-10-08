<?php

namespace App\Http\Requests\Master\DepartmentMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\DepartmentMst;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;

class UpdateDepartmentMstRequest extends FormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(DepartmentMst::class, 'id')],
            'code' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'status' => ['required', new Enum(StatusEnum::class),],
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
