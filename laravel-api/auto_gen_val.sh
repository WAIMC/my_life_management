#!/bin/bash

JSON_FILE="./database/schema/pgsql-schema.json"

BASE_DIR="app/Http/Requests"

camel_case() {
    echo "$1" | sed 's/_/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) tolower(substr($i,2))}1' | tr -d ' '
}

get_folder() {
    local table="$1"
    if [[ "$table" == *"_mst_hist" ]]; then
        echo "History/Master"
    elif [[ "$table" == *"_mgmt_hist" ]]; then
        echo "Management/Master"
    elif [[ "$table" == *"_mst" ]]; then
        echo "Master"
    elif [[ "$table" == *"_mgmt" ]]; then
        echo "Management"
    else
        echo ""
    fi
}

tables=$(jq -r '.properties | keys[]' "$JSON_FILE")

for table in $tables; do
    if [[ "$table" == *"_mst_hist" ]]; then
        folder="History/Master"
        suffix_type="mst_hist"
        prefix="${table%_mst_hist}"
    elif [[ "$table" == *"_mgmt_hist" ]]; then
        folder="Management/Master"
        suffix_type="mgmt_hist"
        prefix="${table%_mgmt_hist}"
    elif [[ "$table" == *"_mst" ]]; then
        folder="Master"
        suffix_type="mst"
        prefix="${table%_mst}"
    elif [[ "$table" == *"_mgmt" ]]; then
        folder="Management"
        suffix_type="mgmt"
        prefix="${table%_mgmt}"
    else
        continue
    fi

    name=$(camel_case "$table")
    name_scope=$(camel_case "$prefix")

    columns=$(jq -r '.properties."'"$table"'".properties | keys[]' "$JSON_FILE")
    required=$(jq -r '.properties."'"$table"'".required[] // empty' "$JSON_FILE" | tr '\n' ' ')

    id_count=0
    for col in $columns; do
        if [[ $col == *"_id" ]]; then
            ((id_count++))
        fi
    done

    is_intermediate=0
    if [[ "$suffix_type" == "mst" || "$suffix_type" == "mgmt" ]] && [[ $id_count -eq 2 ]]; then
        is_intermediate=1
    fi

    if [[ $is_intermediate -eq 1 ]]; then
        file_types=("List" "Update")
    else
        file_types=("Delete" "List" "Store" "Update")
    fi

    mkdir -p "$BASE_DIR/$folder/$name_scope"

    for ftype in "${file_types[@]}"; do
        filename="${ftype}${name}Request.php"
        filepath="$BASE_DIR/$folder/$name_scope/$filename"

        namespace="App\\Http\\Requests\\${folder//\//\\}\\${name_scope}"

        model_path="${folder//\//\\}\\${name}"

        if [[ "$ftype" == "Delete" ]]; then
            cat << EOF > "$filepath"
<?php

namespace $namespace;

use App\\Constants\\CommonVal;
use App\\Models\\$model_path;
use Illuminate\\Foundation\\Http\\FormRequest;
use Illuminate\\Validation\\Rule;

class ${ftype}${name}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => [
                'required',
                'integer',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists(${name}::class, 'id')
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'ids' => __('message.${table}_id'),
            'ids.*' => __('message.${table}_id'),
        ];
    }
}
EOF
            continue
        fi

        uses="use App\\Constants\\CommonVal;
use Illuminate\\Foundation\\Http\\FormRequest;
use Illuminate\\Validation\\Rule;"

        declare -A enum_set
        declare -A model_set

        rules=""
        attributes=""

        for col in $columns; do
            if [[ "$col" == "created_at" || "$col" == "updated_at" ]]; then
                continue
            fi

            is_required=$(echo "$required" | grep -qw "$col" && echo "yes" || echo "")
            if [[ "$ftype" == "List" ]]; then
                req="'nullable'"
            elif [[ -n "$is_required" ]]; then
                req="'required'"
            else
                req="'nullable'"
            fi

            typ=$(jq -r '.properties."'"$table"'".properties."'"$col"'".type // "null"' "$JSON_FILE")
            fmt=$(jq -r '.properties."'"$table"'".properties."'"$col"'".format // "null"' "$JSON_FILE")
            maxl=$(jq -r '.properties."'"$table"'".properties."'"$col"'".maxLength // "null"' "$JSON_FILE")

            validates=()

            excluded_ids=("author_id" "row_id" "parent_id" "failed_job_ids" "field_id" "tokenable_id")

            if [[ "$col" == "email" ]]; then
                validates+=("'email:rfc,dns'" "'min:' . CommonVal::MIN_VARCHAR" "'max:' . CommonVal::MAX_EMAIL" "Rule::unique(${name}::class, 'email')")
                model_set["$model_path"]=1
            elif [[ "$col" == "phone_number" ]]; then
                validates+=("'string'" "'min:' . CommonVal::MIN_VARCHAR" "'max:' . CommonVal::MAX_PHONE_NUMBER")
            elif [[ "$col" == "gender" ]]; then
                validates+=("new Enum(Gender::class)")
                enum_set["Gender"]=1
            elif [[ "$col" == "status" ]]; then
                validates+=("new Enum(StatusEnum::class)")
                enum_set["StatusEnum"]=1
            elif [[ "$col" == "birth" ]]; then
                validates+=("'date_format:' . CommonVal::DATE_FORMAT" "'after_or_equal:' . CommonVal::MIN_DATE" "'before_or_equal:' . CommonVal::MAX_DATE")
            elif [[ "$col" == "is_active" ]]; then
                validates+=("new Enum(IsActive::class)")
                enum_set["IsActive"]=1
            elif [[ "$col" == "is_delete" ]]; then
                validates+=("new Enum(IsDelete::class)")
                enum_set["IsDelete"]=1
            elif [[ "$col" == *"_id" ]]; then
                add_exists=1
                for ex in "${excluded_ids[@]}"; do
                    if [[ "$col" == "$ex" ]]; then
                        add_exists=0
                        break
                    fi
                done

                validates+=("'numeric'" "'min:' . CommonVal::MIN_INTEGER" "'max:' . CommonVal::MAX_INTEGER")

                if [[ $add_exists -eq 1 ]]; then
                    prefix="${col%_id}"
                    ref_table=""
                    if jq -e '.properties."'"${prefix}_mst"'"' >/dev/null 2>&1 "$JSON_FILE"; then
                        ref_table="${prefix}_mst"
                    elif jq -e '.properties."'"${prefix}_mgmt"'"' >/dev/null 2>&1 "$JSON_FILE"; then
                        ref_table="${prefix}_mgmt"
                    fi
                    if [[ -n "$ref_table" ]]; then
                        ref_folder=$(get_folder "$ref_table")
                        ref_model=$(camel_case "$ref_table")
                        ref_model_path="${ref_folder//\//\\}\\${ref_model}"
                        validates+=("Rule::exists(${ref_model}::class, 'id')")
                        model_set["$ref_model_path"]=1
                    fi
                fi
            else
                if [[ "$fmt" == "date-time" ]]; then
                    validates+=("'date_format:' . CommonVal::DATE_FORMAT" "'after_or_equal:' . CommonVal::MIN_DATE" "'before_or_equal:' . CommonVal::MAX_DATE")
                elif [[ "$typ" == "integer" ]]; then
                    validates+=("'numeric'" "'min:' . CommonVal::MIN_INTEGER" "'max:' . CommonVal::MAX_INTEGER")
                elif [[ "$typ" == "string" ]]; then
                    validates+=("'string'" "'min:' . CommonVal::MIN_VARCHAR")
                    if [[ "$maxl" != "null" ]]; then
                        validates+=("'max:$maxl'")
                    else
                        validates+=("'max:' . CommonVal::MAX_VARCHAR")
                    fi
                elif [[ "$typ" == "boolean" ]]; then
                    validates+=("new Enum(IsActive::class)")
                    enum_set["IsActive"]=1
                fi
            fi

            if [[ ${#validates[@]} -gt 0 ]]; then
                joined_validates=$(IFS=', '; echo "${validates[*]}")
                rules+="            '$col' => [$req, $joined_validates],
"
                attributes+="            '$col' => __('message.$col'),
"
            fi
        done

        if [[ "$ftype" == "List" ]]; then
            rules+="            'from_date' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
            ],
            'to_date' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
                'after:from_date'
            ],
"
            attributes+="            'from_date' => __('message.from_date'),
            'to_date' => __('message.to_date'),
"
        fi

        use_str=""
        for enum in "${!enum_set[@]}"; do
            use_str+="use App\\Enums\\$enum;
"
        done
        for mpath in "${!model_set[@]}"; do
            use_str+="use App\\Models\\$mpath;
"
        done

        if [[ -n "$use_str" ]]; then
            uses+="
$use_str"
        fi

        cat << EOF > "$filepath"
<?php

namespace $namespace;

$uses

class ${ftype}${name}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
$rules
        ];
    }

    public function messages(): array
    {
        return [
$attributes
        ];
    }
}
EOF
    done
done