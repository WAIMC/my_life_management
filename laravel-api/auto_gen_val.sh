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
        echo "History/Management"
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
        folder="History/Management"
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

    # Lấy danh sách cột từ JSON schema và lưu trữ theo đúng thứ tự
    columns=()
    while IFS= read -r col; do
        columns+=("$col")
    done < <(jq -r '.properties."'"$table"'".properties | to_entries[] | .key' "$JSON_FILE")
    
    required=$(jq -r '.properties."'"$table"'".required[] // empty' "$JSON_FILE" | tr '\n' ' ')

    # Kiểm tra xem bảng có trường id hay không
    has_id_field=0
    if echo "$columns" | grep -q "^id$"; then
        has_id_field=1
    fi

    # Đếm số cột dạng *_id
    id_count=0
    for col in "${columns[@]}"; do
        if [[ $col == *"_id" && $col != "id" ]]; then
            ((id_count++))
        fi
    done

    # Bảng trung gian là bảng không có trường id và có từ 2 cột *_id trở lên
    is_intermediate=0
    if [[ $has_id_field -eq 0 && $id_count -ge 2 ]]; then
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
            # Thêm kiểm tra cho foreign keys trong delete
            foreign_key_rules=""
            foreign_key_attrs=""
            
            # Kiểm tra xem bảng có là table trung gian không
            if [[ $is_intermediate -eq 0 ]]; then
                # Lọc các cột *_id để kiểm tra FK
                for col in "${columns[@]}"; do
                    if [[ "$col" == *"_id" && "$col" != "id" ]]; then
                        prefix="${col%_id}"
                        ref_table=""
                        
                        # Tìm bảng tham chiếu
                        if jq -e '.properties."'"${prefix}_mst"'"' >/dev/null 2>&1 "$JSON_FILE"; then
                            ref_table="${prefix}_mst"
                            ref_folder="Master"
                        elif jq -e '.properties."'"${prefix}_mgmt"'"' >/dev/null 2>&1 "$JSON_FILE"; then
                            ref_table="${prefix}_mgmt"
                            ref_folder="Management"
                        fi
                        
                        if [[ -n "$ref_table" ]]; then
                            ref_model=$(camel_case "$ref_table")
                            foreign_key_rules+="            'delete.$col' => [
                'required',
                'integer',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists(${ref_model}::class, 'id')
            ],
"
                            foreign_key_attrs+="            'delete.$col' => __('message.$col'),
"
                        fi
                    fi
                done
            fi
            
            # Chỉ thêm delete array nếu có foreign keys
            delete_section=""
            if [[ -n "$foreign_key_rules" ]]; then
                delete_section="            'delete' => ['required', 'array'],
$foreign_key_rules"
            fi
            
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
$delete_section
        ];
    }

    public function messages(): array
    {
        return [
            'ids' => __('message.${table}_id'),
            'ids.*' => __('message.${table}_id'),
$foreign_key_attrs
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
        
        # Lưu trữ các column *_id của bảng trung gian
        id_columns=()
        if [[ $is_intermediate -eq 1 ]]; then
            for col in "${columns[@]}"; do
                if [[ "$col" == *"_id" && "$col" != "id" ]]; then
                    id_columns+=("$col")
                fi
            done
        fi
        
        # Khởi tạo các biến cho bảng trung gian
        rules_insert=""
        attributes_insert=""
        rules_delete=""
        attributes_delete=""

        # Xử lý từng cột theo đúng thứ tự đã lưu
        for col in "${columns[@]}"; do
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

                # Store và Update kiểm tra tồn tại của các *_id trong bảng tương ứng
                # ngoại trừ các id đặc biệt đã định nghĩa trong excluded_ids
                if [[ $add_exists -eq 1 && ("$ftype" == "Store" || "$ftype" == "Update") ]]; then
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
                
                # Xử lý cho bảng trung gian và update request
                if [[ $is_intermediate -eq 1 && "$ftype" == "Update" ]]; then
                    # Thêm rules cho insert
                    rules_insert+="                '$col' => [$req, $joined_validates],
"
                    attributes_insert+="                '$col' => __('message.$col'),
"
                    
                    # Thêm rules cho delete
                    rules_delete+="                '$col' => [$req, $joined_validates],
"
                    attributes_delete+="                '$col' => __('message.$col'),
"
                else
                    rules+="            '$col' => [$req, $joined_validates],
"
                    attributes+="            '$col' => __('message.$col'),
"
                fi
            fi
        done

        # Xử lý logic cho bảng trung gian trong request Update
        if [[ $is_intermediate -eq 1 && "$ftype" == "Update" ]]; then
            # Thêm rules insert và delete
            rules="            'insert' => ['array', 'nullable'],
            'delete' => ['array', 'nullable'],
            'insert.*' => ['required', 'array'],
            'delete.*' => ['required', 'array'],
"
            attributes="            'insert' => __('message.insert'),
            'delete' => __('message.delete'),
            'insert.*' => __('message.items'),
            'delete.*' => __('message.items'),
"
            
            # Thêm rules cho từng column trong insert
            for col in "${columns[@]}"; do
                if [[ "$col" != "created_at" && "$col" != "updated_at" ]]; then
                    typ=$(jq -r '.properties."'"$table"'".properties."'"$col"'".type // "null"' "$JSON_FILE")
                    fmt=$(jq -r '.properties."'"$table"'".properties."'"$col"'".format // "null"' "$JSON_FILE")
                    maxl=$(jq -r '.properties."'"$table"'".properties."'"$col"'".maxLength // "null"' "$JSON_FILE")
                    
                    col_rules=""
                    
                    # Xác định rule dựa vào kiểu dữ liệu
                    if [[ "$col" == *"_id" ]]; then
                        col_rules="'required', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER"
                        
                        # Tìm bảng tham chiếu và thêm rule exists
                        prefix="${col%_id}"
                        ref_table=""
                        if jq -e '.properties."'"${prefix}_mst"'"' >/dev/null 2>&1 "$JSON_FILE"; then
                            ref_table="${prefix}_mst"
                            ref_folder="Master"
                        elif jq -e '.properties."'"${prefix}_mgmt"'"' >/dev/null 2>&1 "$JSON_FILE"; then
                            ref_table="${prefix}_mgmt"
                            ref_folder="Management"
                        fi
                        
                        if [[ -n "$ref_table" ]]; then
                            ref_model=$(camel_case "$ref_table")
                            col_rules+=", Rule::exists(${ref_model}::class, 'id')"
                            uses+="
use App\\Models\\${ref_folder//\//\\}\\$ref_model;"
                        fi
                    elif [[ "$fmt" == "date-time" ]]; then
                        col_rules="'required', 'date_format:' . CommonVal::DATE_FORMAT, 'after_or_equal:' . CommonVal::MIN_DATE, 'before_or_equal:' . CommonVal::MAX_DATE"
                    elif [[ "$typ" == "integer" ]]; then
                        col_rules="'required', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER"
                    elif [[ "$typ" == "string" ]]; then
                        if [[ "$maxl" != "null" ]]; then
                            col_rules="'required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:$maxl'"
                        else
                            col_rules="'required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR"
                        fi
                    elif [[ "$typ" == "boolean" ]]; then
                        col_rules="'required', 'boolean'"
                    else
                        col_rules="'nullable'"
                    fi
                    
                    if [[ -n "$col_rules" ]]; then
                        rules+="            'insert.*.$col' => [$col_rules],
            'delete.*.$col' => [$col_rules],
"
                        attributes+="            'insert.*.$col' => __('message.$col'),
            'delete.*.$col' => __('message.$col'),
"
                    fi
                fi
            done
            
            # Thêm rule kiểm tra combination của các khóa chính (các cột *_id)
            if [[ ${#id_columns[@]} -ge 2 ]]; then
                # Tạo rule kiểm tra tồn tại cụm primary key
                rules+="            'insert.*.combination' => ['required', function (\$attribute, \$value, \$fail) {
                \$parts = explode('.', \$attribute);
                \$index = \$parts[1];
"
                
                # Tạo câu lệnh kiểm tra sự tồn tại
                rules+="                \$exists = ${name}::where("
                first=1
                for id_col in "${id_columns[@]}"; do
                    if [[ $first -eq 1 ]]; then
                        rules+="'$id_col', \$this->input(\"insert.{\$index}.$id_col\")"
                        first=0
                    else
                        rules+=")
                    ->where('$id_col', \$this->input(\"insert.{\$index}.$id_col\")"
                    fi
                done
                rules+=")->exists();
                
                if (\$exists) {
                    \$fail('Bản ghi với các khóa này đã tồn tại.');
                }
            }],
"
                uses+="
use App\\Models\\$model_path;"
            fi
            
        elif [[ "$ftype" == "List" ]]; then
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