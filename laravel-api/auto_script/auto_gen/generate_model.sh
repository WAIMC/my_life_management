#!/bin/bash

# Đặt ROOT_PATH là thư mục gốc của project (có thể override từ biến môi trường hoặc truyền vào script)
get_root_path() {
  if [[ -n "$PROJECT_ROOT" ]]; then
    echo "$PROJECT_ROOT"
  else
    # Mặc định: lấy parent directory của script (giả định script nằm trong auto_scripts/auto_gen_model)
    cd "$(dirname "$0")/../.." && pwd
  fi
}

ROOT_PATH="$(get_root_path)"
JSON_FILE="$ROOT_PATH/auto_script/dataSchema/schema.json"
BASE_MODEL_PATH="$ROOT_PATH/app/Models"

# Hàm để capitalize string (ví dụ: admin_mst -> AdminMst)
capitalize() {
  echo "$1" | sed -r 's/(^|_)([a-z])/\U\2/g' | sed 's/_//g'
}

# Parse JSON và loop qua từng table (sử dụng process substitution để tránh subshell)
while IFS= read -r table; do
  # Lấy table_name
  table_name=$(echo "$table" | jq -r '.table_name')

  # Xác định folder dựa trên hậu tố
  if [[ "$table_name" == *"_mst_hist" ]]; then
    folder="History/Master"
  elif [[ "$table_name" == *"_mgmt_hist" ]]; then
    folder="History/Management"
  elif [[ "$table_name" == *"_mst" ]]; then
    folder="Master"
  elif [[ "$table_name" == *"_mgmt" || "$table_name" == *"_management_mst" ]]; then  # Bao gồm department_management_mst nếu cần
    folder="Management"
  else
    echo "Bỏ qua table $table_name (không khớp hậu tố)."
    continue
  fi

  # Tạo class name (capitalize table_name mà không có _hist nếu có)
  class_name=$(capitalize "${table_name}")

  # Namespace đầy đủ
  namespace="App\\Models\\${folder//\//\\}"

  # Path file output
  output_dir="$BASE_MODEL_PATH/$folder"
  mkdir -p "$output_dir"  # Tạo folder nếu chưa có
  output_file="$output_dir/${class_name}.php"

  # Lấy columns để build $fillable và $casts
  columns=$(echo "$table" | jq -c '.columns[]')
  fillable_array=()
  casts_array=()

  # Sử dụng process substitution để tránh subshell
  while IFS= read -r col; do
    col_name=$(echo "$col" | jq -r '.name')
    col_type=$(echo "$col" | jq -r '.type')

    # Thêm vào fillable nếu không phải id, created_at, updated_at
    if [[ "$col_name" != "id" && "$col_name" != "created_at" && "$col_name" != "updated_at" ]]; then
      fillable_array+=("'$col_name'")
    fi

    # Map casts
    case "$col_type" in
      integer|smallint|bigint) cast_type="integer" ;;
      "character varying"|text) cast_type="string" ;;
      timestamp) cast_type="datetime" ;;
      boolean) cast_type="boolean" ;;
      *) cast_type="" ;;  # Bỏ qua nếu không map
    esac

    if [[ -n "$cast_type" ]]; then
      casts_array+=("'$col_name' => '$cast_type'")
    fi
  done < <(echo "$columns")

  # Build string cho fillable và casts: mỗi item 1 dòng, indent 4 spaces, kết thúc bằng dấu ,
  fillable_str=$(printf "        %s,\n" "${fillable_array[@]}")
  casts_str=$(printf "        %s,\n" "${casts_array[@]}")

  # Generate nội dung file model
  cat <<EOF > "$output_file"
<?php

namespace $namespace;

use Illuminate\Database\Eloquent\Model;

class $class_name extends Model
{
    protected \$table = '$table_name';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected \$fillable = [
$fillable_str
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected \$casts = [
$casts_str
    ];
}
EOF

  echo "Generated model: $output_file"
done < <(jq -c '.[]' "$JSON_FILE")

echo "Generate models complete."