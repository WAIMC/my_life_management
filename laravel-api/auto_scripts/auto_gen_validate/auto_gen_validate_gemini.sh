#!/bin/bash

# generate_requests.sh
# Tác giả: Senior Back-end Developer
# Chức năng: Tự động tạo các file Laravel Request (Validate) dựa trên schema JSON và logic tùy chỉnh.

# === CẤU HÌNH ===
SCHEMA_FILE="./database/schema/pgsql-schema.json"
OUTPUT_DIR="App/Http/Requests" # Thư mục gốc để tạo Requests

# Kiểm tra sự tồn tại của jq
if ! command -v jq &> /dev/null
then
    echo "Lỗi: Công cụ 'jq' chưa được cài đặt. Vui lòng cài đặt (e.g., sudo apt install jq)."
    exit 1
fi

# Tạo thư mục đầu ra nếu chưa có
mkdir -p "$OUTPUT_DIR"

# Lấy danh sách tất cả các table names từ "properties"
TABLES=$(jq -r '.properties | keys[]' "$SCHEMA_FILE")

# === HÀM XỬ LÝ CHÍNH ===

# Hàm chuyển đổi tên table_name_example sang TableNameExample (CamelCase, có viết hoa chữ cái đầu)
to_camel_case() {
    echo "$1" | awk -F'_' '{
        for (i=1; i<=NF; i++) {
            printf "%s", toupper(substr($i, 1, 1)) tolower(substr($i, 2))
        }
        print ""
    }'
}

# Hàm xác định hậu tố và thư mục con (scope)
get_scope_info() {
    local table_name=$1
    local scope_name=""
    local folder_suffix=""

    if [[ "$table_name" =~ _mst$ ]]; then
        scope_name=$(echo "$table_name" | sed 's/_mst$//')
        folder_suffix="Master"
    elif [[ "$table_name" =~ _mgmt$ ]]; then
        scope_name=$(echo "$table_name" | sed 's/_mgmt$//')
        folder_suffix="Management"
    elif [[ "$table_name" =~ _mgmt_hist$ ]]; then
        scope_name=$(echo "$table_name" | sed 's/_mgmt_hist$//')
        folder_suffix="History/Management"
    elif [[ "$table_name" =~ _mst_hist$ ]]; then
        scope_name=$(echo "$table_name" | sed 's/_mst_hist$//')
        folder_suffix="History/Master"
    else
        # Xử lý trường hợp không có hậu tố chuẩn, coi như không có hậu tố
        scope_name=$table_name
        folder_suffix=""
    fi

    # Chuyển đổi tên scope sang CamelCase
    SCOPE_NAME_CC=$(to_camel_case "$scope_name")

    echo "$SCOPE_NAME_CC" "$folder_suffix"
}

# Hàm tạo nội dung rules dựa trên schema column
generate_rules() {
    local table_name=$1
    local validation_type=$2 # Ví dụ: 'Store', 'Update', 'List'
    local field_name=$3
    local field_type=$4
    local is_required=$5
    local max_length=$6
    
    local rules=()

    # Thêm 'required' hoặc 'nullable' (theo logic ưu tiên trong test.php)
    if [[ "$validation_type" == *"ListRequest"* ]]; then
        rules+=("'nullable'") # Ưu tiên ListRequest: tất cả là nullable
    elif [[ "$is_required" == "true" ]]; then
        rules+=("'required'")
    else
        rules+=("'nullable'")
    fi
    
    # Logic validate theo type
    if [[ "$field_type" == "integer" ]]; then
        rules+=("'integer'")
        if [[ "$field_name" == *"_id"* && "$field_name" != "author_id" && "$field_name" != "row_id" && "$field_name" != "parent_id" && "$field_name" != "failed_job_ids" && "$field_name" != "field_id" && "$field_name" != "tokenable_id" ]]; then
            # Kiểm tra FK tồn tại.
            # Lưu ý: Đây chỉ là logic, không thể tự động biết Model, cần phải có cơ chế Map ngoài.
            # Trong script này, tôi chỉ tạo ra phần logic để Dev tự điền:
            rules+=("'exists:TABLE_NAME,id'") 
        fi
    elif [[ "$field_type" == "string" ]]; then
        rules+=("'string'")
        if [[ -n "$max_length" && "$max_length" -gt 0 ]]; then
            rules+=("'max:$max_length'")
        fi
        
        # Logic đặc biệt cho email
        if [[ "$field_name" == "email" ]]; then
            rules+=("'email'")
            # Giả định thêm Rule::unique cho email trong Store/Update Request (nếu không phải List)
            if [[ "$validation_type" == "Store"* || "$validation_type" == "Update"* ]]; then
                 rules+=("Rule::unique({$SCOPE_NAME_CC}::class, 'email')->ignore(request('id'))")
            fi
        fi
    fi
    
    # Logic Enum/Custom Rules (từ test.php)
    case "$field_name" in
        "gender"|"is_active")
            rules+=("Rule::in(Gender::getValues())") # Giả định Gender/IsActive dùng Rule::in(Enum::getValues())
            ;;
        "status")
            rules+=("Rule::in(StatusEnum::getValues())")
            ;;
    esac

    # Trả về chuỗi rules
    echo "$(IFS=,; echo "${rules[*]}")"
}

# Hàm tạo file Request (logic tạo nội dung PHP)
create_request_file() {
    local table_name=$1
    local validation_type=$2
    local file_path=$3
    local table_data=$4 # JSON data cho table
    local scope_name_cc=$5 # Tên scope CamelCase

    # Lấy ra danh sách các column cần tạo rules
    # Bỏ qua created_at và updated_at
    COLUMNS=$(echo "$table_data" | jq -r '.properties | keys[]' | grep -v 'created_at\|updated_at')

    RULES_CONTENT=""
    USE_STATEMENTS=""
    USE_SET=()

    for col_name in $COLUMNS; do
        # Lấy thông tin chi tiết của column
        COL_INFO=$(echo "$table_data" | jq -r ".properties.\"$col_name\" | {type: .type, maxLength: .maxLength // 0, isRequired: (\"$table_data\" | jq \".required | index(\\\"$col_name\\\")\")}")
        
        FIELD_TYPE=$(echo "$COL_INFO" | jq -r '.type')
        MAX_LENGTH=$(echo "$COL_INFO" | jq -r '.maxLength')
        IS_REQUIRED=$(echo "$COL_INFO" | jq -r 'if .isRequired != null then "true" else "false" end')

        # Bắt đầu tạo rules
        RULES=$(generate_rules "$table_name" "$validation_type" "$col_name" "$FIELD_TYPE" "$IS_REQUIRED" "$MAX_LENGTH")
        
        # Thêm rules vào nội dung
        RULES_CONTENT+="            '$col_name' => [\n                $RULES\n            ],\n"

        # Logic tạo USE statements theo logic test.php
        if [[ "$col_name" == "is_active" || "$col_name" == "is_delete" ]]; then
            USE_STATEMENTS+=$([[ ! " ${USE_SET[*]} " =~ " App\\\\Enums\\\\IsActive " ]] && echo "use App\\Enums\\IsActive;\n")
            USE_SET+=(" App\\\\Enums\\\\IsActive ")
        elif [[ "$col_name" == "gender" ]]; then
            USE_STATEMENTS+=$([[ ! " ${USE_SET[*]} " =~ " App\\\\Enums\\\\Gender " ]] && echo "use App\\Enums\\Gender;\n")
            USE_SET+=(" App\\\\Enums\\\\Gender ")
        elif [[ "$col_name" == "status" ]]; then
            USE_STATEMENTS+=$([[ ! " ${USE_SET[*]} " =~ " App\\\\Enums\\\\StatusEnum " ]] && echo "use App\\Enums\\StatusEnum;\n")
            USE_SET+=(" App\\\\Enums\\\\StatusEnum ")
        fi

        # Logic cho FK (Model Use)
        if [[ "$col_name" == *"_id"* && "$col_name" != "author_id" && "$col_name" != "row_id" && "$col_name" != "parent_id" && "$col_name" != "failed_job_ids" && "$col_name" != "field_id" && "$col_name" != "tokenable_id" ]]; then
            MODEL_NAME=$(echo "$col_name" | sed 's/_id$//')
            MODEL_NAME_CC=$(to_camel_case "$MODEL_NAME")
            USE_STATEMENTS+=$([[ ! " ${USE_SET[*]} " =~ " App\\\\Models\\\\$MODEL_NAME_CC " ]] && echo "use App\\Models\\$MODEL_NAME_CC;\n")
            USE_SET+=(" App\\\\Models\\\\$MODEL_NAME_CC ")
        fi

        # Logic cho Rule::unique (Model Use)
        if [[ "$col_name" == "email" && ("$validation_type" == "Store"* || "$validation_type" == "Update"*) ]]; then
            USE_STATEMENTS+=$([[ ! " ${USE_SET[*]} " =~ " App\\\\Models\\\\$scope_name_cc " ]] && echo "use App\\Models\\$scope_name_cc;\n")
            USE_SET+=(" App\\\\Models\\\\$scope_name_cc ")
        fi

    done

    # Logic đặc biệt cho ListRequest
    if [[ "$validation_type" == *"ListRequest"* ]]; then
        RULES_CONTENT+="            'from_date' => [\n                'nullable',\n                'date_format:Y-m-d H:i:s',\n            ],\n"
        RULES_CONTENT+="            'to_date' => [\n                'nullable',\n                'date_format:Y-m-d H:i:s',\n            ],\n"
    fi
    
    # Xóa ký tự xuống dòng cuối cùng trong USE_STATEMENTS nếu có
    USE_STATEMENTS=$(echo -e "$USE_STATEMENTS" | sed '/^\s*$/d')

    # Thay thế {use} bằng các use statements đã thu thập
    # Sử dụng cat + heredoc để tạo file PHP
    cat << EOF > "$file_path"
<?php

namespace App\Http\Requests\\$folder_path;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
$USE_STATEMENTS
class $validation_type extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
$RULES_CONTENT
        ];
    }
}
EOF
    echo "Đã tạo: $file_path"
}

# === BẮT ĐẦU VÒNG LẶP CHO TỪNG TABLE ===

echo "Bắt đầu tạo Request files..."

for TABLE_NAME in $TABLES; do
    # 1. Phân loại table
    read -r SCOPE_NAME_CC FOLDER_SUFFIX <<< $(get_scope_info "$TABLE_NAME")
    
    # Lấy table data (JSON)
    TABLE_DATA=$(jq -c ".properties.\"$TABLE_NAME\"" "$SCHEMA_FILE")

    # Đếm số lượng cột *_id (ngoại trừ các cột đặc biệt) để xác định table trung gian
    # Trong schema JSON này, table trung gian được xác định bằng cách có 2 FK trong required
    # Vì logic trong test.php không hoàn toàn rõ ràng về "2 column có dạng *_id",
    # tôi sẽ ưu tiên logic hậu tố và số lượng `required` field là FK.
    
    # Đếm các field là *_id và được required
    FK_COUNT=$(echo "$TABLE_DATA" | jq -r '.required[]' | grep -E '(_id$)' | grep -v 'author_id\|row_id\|parent_id\|failed_job_ids\|field_id\|tokenable_id' | wc -l)
    
    # Tên folder trong App\Http\Requests
    folder_path=""
    if [[ -n "$FOLDER_SUFFIX" ]]; then
        folder_path="$FOLDER_SUFFIX/$SCOPE_NAME_CC"
    else
        folder_path="$SCOPE_NAME_CC"
    fi
    
    FULL_FOLDER_PATH="$OUTPUT_DIR/$folder_path"
    mkdir -p "$FULL_FOLDER_PATH"
    
    # 2. Tạo tên Request file
    
    # Table trung gian (logic: 2 hoặc nhiều hơn FK được required, và không có các cột khác ngoài 2 FK, created_at, updated_at)
    # Lấy tất cả required fields:
    REQUIRED_FIELDS=$(echo "$TABLE_DATA" | jq -r '.required[]')
    
    # Giả định table trung gian (pivot table) là table chỉ có 2 column FK trong required (như admin_department_mst)
    if [[ "$FK_COUNT" -ge 2 && $(echo "$REQUIRED_FIELDS" | wc -l) -le 2 ]]; then
        # Table trung gian: ListRequest, UpdateRequest
        
        # Update Request (ví dụ: UpdateAdminDepartmentMstRequest)
        FILE_NAME="Update${SCOPE_NAME_CC}Request"
        create_request_file "$TABLE_NAME" "$FILE_NAME" "$FULL_FOLDER_PATH/$FILE_NAME.php" "$TABLE_DATA" "$SCOPE_NAME_CC"

        # List Request (ví dụ: AdminDepartmentMstListRequest)
        FILE_NAME="${SCOPE_NAME_CC}ListRequest"
        create_request_file "$TABLE_NAME" "$FILE_NAME" "$FULL_FOLDER_PATH/$FILE_NAME.php" "$TABLE_DATA" "$SCOPE_NAME_CC"

    else
        # Table thông thường: DeleteRequest, ListRequest, StoreRequest, UpdateRequest

        # Delete Request (ví dụ: DeleteAdminMstRequest)
        FILE_NAME="Delete${SCOPE_NAME_CC}Request"
        # Delete chỉ cần rules cho 'id' (giả định)
        DELETE_DATA=$(echo '{"properties": {"id": {"type": "integer"}}, "required": ["id"]}')
        create_request_file "$TABLE_NAME" "$FILE_NAME" "$FULL_FOLDER_PATH/$FILE_NAME.php" "$DELETE_DATA" "$SCOPE_NAME_CC"
        
        # List Request (ví dụ: AdminMstListRequest)
        FILE_NAME="${SCOPE_NAME_CC}ListRequest"
        create_request_file "$TABLE_NAME" "$FILE_NAME" "$FULL_FOLDER_PATH/$FILE_NAME.php" "$TABLE_DATA" "$SCOPE_NAME_CC"

        # Store Request (ví dụ: StoreAdminMstRequest)
        FILE_NAME="Store${SCOPE_NAME_CC}Request"
        create_request_file "$TABLE_NAME" "$FILE_NAME" "$FULL_FOLDER_PATH/$FILE_NAME.php" "$TABLE_DATA" "$SCOPE_NAME_CC"

        # Update Request (ví dụ: UpdateAdminMstRequest)
        FILE_NAME="Update${SCOPE_NAME_CC}Request"
        # Update cần thêm 'id' vào required (giả định)
        UPDATE_DATA=$(echo "$TABLE_DATA" | jq '.required += ["id"]')
        create_request_file "$TABLE_NAME" "$FILE_NAME" "$FULL_FOLDER_PATH/$FILE_NAME.php" "$UPDATE_DATA" "$SCOPE_NAME_CC"

    fi
done

echo "Hoàn tất tạo Request files tại thư mục $OUTPUT_DIR."

# Xóa các file rỗng nếu có (do lỗi logic)
find "$OUTPUT_DIR" -type f -empty -delete