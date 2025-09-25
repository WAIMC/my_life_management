#!/bin/bash
# generate_validation_requests.sh
# Tạo các FormRequest classes từ JSON schema của PostgreSQL

# Kiểm tra xem jq đã cài chưa
if ! command -v jq &> /dev/null; then
    echo "Yêu cầu cài đặt jq (trang web: https://stedolan.github.io/jq/)."
    exit 1
fi

# Kiểm tra tham số đầu vào (file JSON schema)
if [ $# -lt 1 ]; then
    echo "Usage: $0 schema.json"
    exit 1
fi
SCHEMA_FILE="$1"

# Thư mục gốc để tạo các Request
BASE_DIR="App/Http/Requests"

# Lặp qua từng bảng (key) trong JSON schema
tables=$(jq -r '.properties | keys[]' "$SCHEMA_FILE")
for table in $tables; do
    # Xác định thư mục đích dựa vào hậu tố tên bảng
    if [[ "$table" == *_mgmt_hist ]]; then
        subdir="Management/Master"
    elif [[ "$table" == *_mst_hist ]]; then
        subdir="History/Master"
    elif [[ "$table" == *_mgmt ]]; then
        subdir="Management"
    elif [[ "$table" == *_mst ]]; then
        subdir="Master"
    else
        # Nếu không khớp suffix cần thiết, bỏ qua
        continue
    fi

    # Đường dẫn đầy đủ đến thư mục (nếu subdir rỗng thì chỉ lấy gốc)
    if [ -n "$subdir" ]; then
        dir="$BASE_DIR/$subdir"
    else
        dir="$BASE_DIR"
    fi

    # Kiểm tra bảng trung gian: đúng 2 cột kết thúc bằng "_id" và không có cột "id"
    cols=$(jq -r ".properties[\"$table\"].properties | keys[]" "$SCHEMA_FILE")
    idColsCount=$(echo "$cols" | grep -E "_id$" | wc -l)
    hasId=$(echo "$cols" | grep -w "id")
    if [ "$idColsCount" -eq 2 ] && [ -z "$hasId" ]; then
        intermediate=true
    else
        intermediate=false
    fi

    # Chuyển tên bảng sang PascalCase làm tên lớp
    pascal=$(echo "$table" | awk -F_ '{ for (i=1;i<=NF;i++) printf toupper(substr($i,1,1)) substr($i,2) }')

    # Tạo thư mục nếu chưa có
    mkdir -p "$dir"

    # Quyết định các loại Request cần tạo
    if [ "$intermediate" = true ]; then
        requests=("List" "Update")
    else
        requests=("Delete" "List" "Store" "Update")
    fi

    # Xác định namespace dựa trên subdir
    ns="App\\Http\\Requests"
    if [ -n "$subdir" ]; then
        ns+="\\${subdir//\//\\}"
    fi

    # Tạo từng file FormRequest
    for req in "${requests[@]}"; do
        case $req in
            Delete)
                className="Delete${pascal}Request"
                ;;
            List)
                className="${pascal}ListRequest"
                ;;
            Store)
                className="Store${pascal}Request"
                ;;
            Update)
                className="Update${pascal}Request"
                ;;
        esac
        filePath="$dir/${className}.php"

        # Viết phần đầu của file PHP
        cat > "$filePath" <<EOF
<?php

namespace $ns;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
EOF
        # Nếu là DeleteRequest, thêm use Rule
        if [ "$req" = "Delete" ]; then
            echo "use Illuminate\Validation\Rule;" >> "$filePath"
        fi
        echo "" >> "$filePath"

        cat >> "$filePath" <<EOF
class $className extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
EOF

        # Xử lý nội dung rules tùy loại Request
        if [ "$req" = "Delete" ]; then
            # DeleteRequest chỉ có ids array
            echo "            'ids' => 'required|array'," >> "$filePath"
            # Xác định tên trường id chính để kiểm tồn tại
            idField="id"
            if echo "$cols" | grep -qw "id"; then
                idField="id"
            else
                idField=$(echo "$cols" | grep -E "_id$" | head -n1)
            fi
            echo "            'ids.*' => ['required','integer','min:CommonVal::MIN_INTEGER','max:CommonVal::MAX_INTEGER', Rule::exists('$table', '$idField')]," >> "$filePath"
        else
            # ListRequest thêm 2 field đặc biệt from_date, to_date
            if [ "$req" = "List" ]; then
                echo "            'from_date' => 'nullable|date_format:CommonVal::DATE_FORMAT|after_or_equal:CommonVal::MIN_DATE|before_or_equal:CommonVal::MAX_DATE'," >> "$filePath"
                echo "            'to_date'   => 'nullable|date_format:CommonVal::DATE_FORMAT|after_or_equal:CommonVal::MIN_DATE|before_or_equal:CommonVal::MAX_DATE'," >> "$filePath"
            fi
            # Còn lại xử lý từng cột trong bảng
            for col in $cols; do
                # Bỏ qua cột "id" (không validate trong store/update thông thường)
                if [ "$col" = "id" ]; then
                    continue
                fi
                # Đọc thông tin type, format, maxLength từ JSON
                colType=$(jq -r ".properties[\"$table\"].properties.\"$col\".type" "$SCHEMA_FILE")
                colFormat=$(jq -r ".properties[\"$table\"].properties.\"$col\".format" "$SCHEMA_FILE")
                colMaxLength=$(jq -r ".properties[\"$table\"].properties.\"$col\".maxLength" "$SCHEMA_FILE")

                # Xây dựng rule tương ứng
                rule="nullable"
                if [ "$colType" = "integer" ]; then
                    rule+="|integer|min:CommonVal::MIN_INTEGER|max:CommonVal::MAX_INTEGER"
                elif [ "$colType" = "string" ]; then
                    rule+="|string"
                    if [ -n "$colMaxLength" ] && [ "$colMaxLength" != "null" ]; then
                        rule+="|max:$colMaxLength"
                    else
                        rule+="|max:CommonVal::MAX_VARCHAR"
                    fi
                elif [ "$colType" = "boolean" ]; then
                    rule+="|boolean"
                elif [ "$colFormat" = "date-time" ]; then
                    rule+="|date_format:CommonVal::DATE_FORMAT|after_or_equal:CommonVal::MIN_DATE|before_or_equal:CommonVal::MAX_DATE"
                fi
                echo "            '$col' => '$rule'," >> "$filePath"
            done
        fi

        cat >> "$filePath" <<EOF
        ];
    }
}
EOF
    done
done


# bash generate_validation_requests.sh ./database/schema/pgsql-schema.json
