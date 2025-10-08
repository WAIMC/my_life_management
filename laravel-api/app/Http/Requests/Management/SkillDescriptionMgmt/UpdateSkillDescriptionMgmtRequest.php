<?php

namespace App\Http\Requests\Management\SkillDescriptionMgmt;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Management\SkillDescriptionMgmt;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;
use App\Models\Management\SkillMgmt;

class UpdateSkillDescriptionMgmtRequest extends FormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(SkillDescriptionMgmt::class, 'id')],
            'parent_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'title' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:100',],
            'summary' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:255',],
            'article' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR,],
            'status' => ['required', new Enum(StatusEnum::class),],
            'is_display' => ['required', new Enum(IsActive::class),],
            'rank_order' => ['required',],
            'skill_mgmt_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER, Rule::exists(SkillMgmt::class, 'id'),],
            'is_delete' => ['required', new Enum(IsDelete::class),],
        ];
    }

    public function attributes(): array
    {
        return [
            'parent_id' => __('messages.parent_id'),
            'title' => __('messages.title'),
            'summary' => __('messages.summary'),
            'article' => __('messages.article'),
            'status' => __('messages.status'),
            'is_display' => __('messages.is_display'),
            'rank_order' => __('messages.rank_order'),
            'skill_mgmt_id' => __('messages.skill_mgmt_id'),
            'is_delete' => __('messages.is_delete'),
        ];
    }
}
