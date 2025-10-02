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
JSON_FILE="$ROOT_PATH/auto_scripts/dataSchema/schema.json"
INTERFACE_PATH="$ROOT_PATH/app/Interfaces"
REPO_PATH="$ROOT_PATH/app/Repositories"

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

  # Tạo class name (capitalize table_name)
  class_name=$(capitalize "${table_name}")

  # Namespace đầy đủ cho interface và repo
  namespace_interface="App\\Interfaces\\${folder//\//\\}"
  namespace_repo="App\\Repositories\\${folder//\//\\}"
  model_namespace="App\\Models\\${folder//\//\\}"

  # Path file output
  output_dir_interface="$INTERFACE_PATH/$folder"
  mkdir -p "$output_dir_interface"  # Tạo folder nếu chưa có
  interface_file="$output_dir_interface/${class_name}Interface.php"

  output_dir_repo="$REPO_PATH/$folder"
  mkdir -p "$output_dir_repo"
  repo_file="$output_dir_repo/${class_name}Repository.php"

  # Lấy columns để kiểm tra loại table
  columns=$(echo "$table" | jq -c '.columns[]')
  col_names=()
  col_types=()
  has_id=false
  key_cols=()
  has_password=false
  has_user_name=false
  sensitive_cols=("password" "remember_token" "email_verified_at" "is_delete" "created_at")
  select_cols=()
  filter_cols=()
  fillable_cols=()  # Assume from previous model gen, but collect here
  exact_match_cols=("email" "phone_number" "avatar" "birth" "image" "link")

  while IFS= read -r col; do
    col_name=$(echo "$col" | jq -r '.name')
    col_type=$(echo "$col" | jq -r '.type')
    col_names+=("$col_name")
    col_types+=("$col_type")
    if [[ "$col_name" == "id" ]]; then
      has_id=true
    fi
    if [[ "$col_name" == "password" ]]; then
      has_password=true
    fi
    if [[ "$col_name" == "user_name" ]]; then
      has_user_name=true
    fi
    if [[ "$col_name" =~ .+_(mst|mgmt|mst_hist|mgmt_hist)_id$ ]]; then
      key_cols+=("$col_name")
    fi

    # For select, exclude sensitive
    if ! [[ " ${sensitive_cols[*]} " =~ " ${col_name} " ]]; then
      select_cols+=("$col_name")
    fi

    # For filters, exclude id, created_at, updated_at, sensitive
    if [[ "$col_name" != "id" && "$col_name" != "created_at" && "$col_name" != "updated_at" ]] && ! [[ " ${sensitive_cols[*]} " =~ " ${col_name} " ]]; then
      filter_cols+=("$col_name")
    fi

    # Fillable: all except id, created_at, updated_at
    if [[ "$col_name" != "id" && "$col_name" != "created_at" && "$col_name" != "updated_at" ]]; then
      fillable_cols+=("$col_name")
    fi
  done < <(echo "$columns")

  # Xác định có phải table trung gian không
  is_intermediate=false
  if ! $has_id && [ ${#key_cols[@]} -ge 2 ]; then
    is_intermediate=true
  fi

  # Assume 2 key_cols for intermediate
  if $is_intermediate && [ ${#key_cols[@]} -ne 2 ]; then
    echo "Table $table_name has ${#key_cols[@]} key columns, skipping advanced gen."
    continue
  fi

  # Set conditional variables
  param_delete="ids"
  return_store="int"
  store_content=""
  while IFS= read -r line; do
    store_content+="${line}"$'\n'
  done < <(for col in "${fillable_cols[@]}"; do
    if [[ "$col" == "user_name" ]] && $has_user_name; then
      echo "        \$data['$col'] = \$payload['username'] ?? null;"
    elif [[ "$col" == "password" ]] && $has_password; then
      echo "        \$data['$col'] = \$payload['password'] ? Hash::make(\$payload['password']) : null;"
    else
      echo "        \$data['$col'] = \$payload['$col'] ?? null;"
    fi
  done
  echo "        \$this->model->create(\$data);")
  order_by_col="id"
  if $is_intermediate; then
    param_delete="payload"
    return_store="void"
    store_content="        \$this->model->create(\$payload);"
    order_by_col="${key_cols[0]}"
  fi

  # Generate Interface - always overwrite
  cat <<EOF > "$interface_file"
<?php

declare(strict_types=1);

namespace $namespace_interface;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface ${class_name}Interface extends BaseInterface
{
    /**
     * Get list
     *
     * @param array \$payload
     * @return Collection
     */
    public function list(array \$payload): Collection;

    /**
     * Store record
     *
     * @param array \$payload
     * @return $return_store
     */
    public function executeStore(array \$payload): $return_store;
EOF

  if ! $is_intermediate; then
    cat <<EOF >> "$interface_file"

    /**
     * Update record
     *
     * @param array \$payload
     * @return int
     */
    public function executeUpdate(array \$payload): int;
EOF
  fi

  cat <<EOF >> "$interface_file"

    /**
     * Delete record
     *
     * @param array \$${param_delete}
     * @return void
     */
    public function executeDelete(array \$${param_delete}): void;
EOF

  if $is_intermediate; then
    cat <<EOF >> "$interface_file"

    /**
     * Get ids
     *
     * @param array \$tuples
     * @return Collection
     */
    public function get${class_name}Id(array \$tuples): Collection;
EOF
  fi

  cat <<EOF >> "$interface_file"
}
EOF

  echo "Generated interface: $interface_file"

  # Generate Repository - always overwrite
  cat <<EOF > "$repo_file"
<?php

declare(strict_types=1);

namespace $namespace_repo;

use App\Enums\IsDelete;
use $namespace_interface\\${class_name}Interface;
use $model_namespace\\$class_name;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;
$(if $has_password; then echo "use Illuminate\\Support\\Facades\\Hash;"; fi)

class ${class_name}Repository extends BaseRepository implements ${class_name}Interface
{
    public function __construct($class_name \$model)
    {
        parent::__construct(\$model);
    }

    /**
     * Get list
     *
     * @param array \$payload
     * @return Collection
     */
    public function list(array \$payload): Collection
    {
        \$query = \$this->model->query()
            ->select([
$(for col in "${select_cols[@]}"; do echo "                '$col'," ; done)
            ]);

EOF

  for col in "${filter_cols[@]}"; do
    # Find type and decide operator
    index=0
    for c in "${col_names[@]}"; do
      if [[ "$c" == "$col" ]]; then
        break
      fi
      index=$((index+1))
    done
    type="${col_types[$index]}"
    use_like=false
    if [[ "$type" == "character varying" || "$type" == "text" ]]; then
      if ! [[ " ${exact_match_cols[*]} " =~ " ${col} " ]]; then
        use_like=true
      fi
    fi

    if $use_like; then
      cat <<EOF >> "$repo_file"
        if (isset(\$payload['$col'])) {
            \$query->where('$col', 'like', '%' . \$payload['$col'] . '%');
        }

EOF
    else
      cat <<EOF >> "$repo_file"
        if (isset(\$payload['$col'])) {
            \$query->where('$col', \$payload['$col']);
        }

EOF
    fi
  done

  cat <<EOF >> "$repo_file"
        if (isset(\$payload['from_date'])) {
            \$fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, \$payload['from_date']);
            \$query->whereDate('updated_at', '>=', \$fromDate);
        }

        if (isset(\$payload['to_date'])) {
            \$toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, \$payload['to_date']);
            \$query->whereDate('updated_at', '<=', \$toDate);
        }

        \$query->orderBy('$order_by_col');

        return \$query->get();
    }

    /**
     * Create new record
     *
     * @param array \$payload
     * @return $return_store
     */
    public function executeStore(array \$payload): $return_store
    {
$store_content
EOF

  if ! $is_intermediate; then
    cat <<EOF >> "$repo_file"
        return \$this->model->id;
EOF
  fi

  cat <<EOF >> "$repo_file"
    }

EOF

  if ! $is_intermediate; then
    cat <<EOF >> "$repo_file"

    /**
     * Update record
     *
     * @param array \$payload
     * @return int
     */
    public function executeUpdate(array \$payload): int
    {
        \$record = \$this->model->find(\$payload['id']);
EOF

    for col in "${fillable_cols[@]}"; do
      if [[ "$col" == "user_name" ]] && $has_user_name; then
        cat <<EOF >> "$repo_file"
        \$record['$col'] = \$payload['username'] ?? null;
EOF
      elif [[ "$col" == "password" ]] && $has_password; then
        cat <<EOF >> "$repo_file"
        \$record['$col'] = \$payload['password'] ? Hash::make(\$payload['password']) : null;
EOF
      else
        cat <<EOF >> "$repo_file"
        \$record['$col'] = \$payload['$col'] ?? null;
EOF
      fi
    done

    cat <<EOF >> "$repo_file"
        \$record->save();

        return \$record->id;
    }

EOF
  fi

  cat <<EOF >> "$repo_file"
    /**
     * Delete record
     *
     * @param array \$${param_delete}
     * @return void
     */
    public function executeDelete(array \$${param_delete}): void
    {
EOF

  if $is_intermediate; then
    cat <<EOF >> "$repo_file"
        \$values = collect(\$payload)->map(function (\$item) {
            return '(' . (int)\$item['${key_cols[0]}'] . ', ' . (int)\$item['${key_cols[1]}'] . ')';
        })->all();

        \$this->model
            ->whereRaw("(${key_cols[0]}, ${key_cols[1]}) IN (" . implode(", ", \$values) . ")")
            ->delete();
EOF
  else
    cat <<EOF >> "$repo_file"
        \$this->model->whereIn('id', \$ids)->update(['is_delete' => IsDelete::TRUE->value]);
EOF
  fi

  cat <<EOF >> "$repo_file"
    }

EOF

  if $is_intermediate; then
    cat <<EOF >> "$repo_file"
    /**
     * Get ids
     *
     * @param array \$tuples
     * @return Collection
     */
    public function get${class_name}Id(array \$tuples): Collection
    {
        \$values = collect(\$tuples)->map(function (\$item) {
            return '(' . (int)\$item['${key_cols[0]}'] . ', ' . (int)\$item['${key_cols[1]}'] . ')';
        })->all();

        return \$this->model
            ->whereRaw("(${key_cols[0]}, ${key_cols[1]}) IN (" . implode(", ", \$values) . ")")
            ->pluck('${key_cols[0]}', '${key_cols[1]}');
    }
EOF
  fi

  cat <<EOF >> "$repo_file"
}
EOF

  echo "Generated repository: $repo_file"

done < <(jq -c '.[]' "$JSON_FILE")

echo "Generate repositories and interfaces complete."