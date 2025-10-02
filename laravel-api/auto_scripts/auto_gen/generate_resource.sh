#!/bin/bash

# Shell script to generate Laravel Resource PHP files based on PostgreSQL        # For columns with SQL keywords as names, we need special handling
        if [[ "$field" == "column" || "$field" == "table" || "$field" == "index" ]]; then
            # For SQL keywords, we'll assume a string type as default
            type="varchar"
            format=""
        else
            type=$(jq -r --arg tbl "$table" --arg fld "$field" '.[] | select(.table_name==$tbl) | .columns[] | select(.name==$fld) | .type' "$SCHEMA_FILE")
            format=$(jq -r --arg tbl "$table" --arg fld "$field" '.[] | select(.table_name==$tbl) | .columns[] | select(.name==$fld) | .format // empty' "$SCHEMA_FILE")
        fiSON schema.
# Assumes the schema file is named 'pgsql-schema.json' in the current directory.
# Generates files in App/Http/Resources/... structure, overwriting if exists.
# Excludes fields: created_at, and any containing 'token' or 'verified_at'.
# Casts types accordingly, formats 'updated_at' specially if present.

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
SCHEMA_FILE="$ROOT_PATH/auto_scripts/dataSchema/schema.json"
ROOT_DIR="$ROOT_PATH/app/Http/Resources"

# Function to convert table name to CamelCase Resource name, e.g., admin_mst -> AdminMstResource
to_camel_case() {
    local name="$1"
    name="${name//_hist/}"  # Remove _hist suffix for class name if present
    name="${name//_mgmt/}"  # Remove _mgmt suffix for class name if present
    name="${name//_mst/}"   # Remove _mst suffix for class name if present
    IFS='_' read -r -a parts <<< "$name"
    for i in "${!parts[@]}"; do
        parts[$i]="${parts[$i]^}"
    done
    # Join with no spaces and add Resource suffix
    local joined=""
    for part in "${parts[@]}"; do
        joined="${joined}${part}"
    done
    echo "${joined}Resource"
}

# Function to determine namespace and subdir based on table suffix
get_namespace_and_dir() {
    local table="$1"
    if [[ "$table" == *"_mst_hist" ]]; then
        echo "App\\\\Http\\\\Resources\\\\History\\\\Master" "History/Master"
    elif [[ "$table" == *"_mgmt_hist" ]]; then
        echo "App\\\\Http\\\\Resources\\\\History\\\\Management" "History/Management"
    elif [[ "$table" == *"_mst" ]]; then
        echo "App\\\\Http\\\\Resources\\\\Master" "Master"
    elif [[ "$table" == *"_mgmt" ]]; then
        echo "App\\\\Http\\\\Resources\\\\Management" "Management"
    else
        echo "App\\\\Http\\\\Resources" ""  # Default, though all seem to have suffixes
    fi
}

# Function to check if table is intermediate (no 'id', >=2 *_id columns)
is_intermediate_table() {
    local table="$1"
    local has_id=$(jq -r '.[] | select(.table_name=="'"$table"'") | .columns[] | select(.name=="id") | .name // empty' "$SCHEMA_FILE")
    if [[ -n "$has_id" ]]; then
        return 1  # Has id, not intermediate
    fi

    local id_count=0
    local fields=$(jq -r '.[] | select(.table_name=="'"$table"'") | .columns[].name' "$SCHEMA_FILE")
    for field in $fields; do
        if [[ "$field" == *"_id" ]] && [[ "$field" == *"_mst_id" || "$field" == *"_mgmt_id" || "$field" == *"_mst_hist_id" || "$field" == *"_mgmt_hist_id" ]]; then
            ((id_count++))
        fi
    done

    if (( id_count >= 2 )); then
        return 0  # Intermediate
    else
        return 1
    fi
}

# Function to generate the toArray content
generate_toarray() {
    local table="$1"
    local fields=$(jq -r '.[] | select(.table_name=="'"$table"'") | .columns[].name' "$SCHEMA_FILE" | sort)
    local output=""

    for field in $fields; do
        # Exclude specific fields
        if [[ "$field" == "created_at" ]] || [[ "$field" == *"token"* ]] || [[ "$field" == *"verified_at" ]]; then
            continue
        fi

        local type=$(jq -r '.[] | select(.table_name=="'"$table"'") | .columns[] | select(.name=="'"$field"'") | .type' "$SCHEMA_FILE")
        local format=$(jq -r '.[] | select(.table_name=="'"$table"'") | .columns[] | select(.name=="'"$field"'") | .format // empty' "$SCHEMA_FILE")

        local cast=""
        if [[ "$type" == *"int"* ]]; then
            cast="(int)"
        elif [[ "$type" == *"varchar"* || "$type" == *"text"* || "$type" == *"char"* ]]; then
            if [[ "$field" == "updated_at" ]]; then
                cast="(string)date(CommonVal::DATE_FORMAT, strtotime("
                output+="            '$field' => ${cast}\$this->$field)),\n"
                continue
            else
                cast="(string)"
            fi
        elif [[ "$type" == *"timestamp"* || "$type" == *"date"* ]]; then
            if [[ "$field" == "updated_at" ]]; then
                cast="(string)date(CommonVal::DATE_FORMAT, strtotime("
                output+="            '$field' => ${cast}\$this->$field)),\n"
                continue
            else
                cast="(string)"
            fi
        elif [[ "$type" == *"bool"* ]]; then
            cast="(int)"  # As per example, cast bool to int
        else
            cast="(string)"  # Default
        fi

        output+="            '$field' => ${cast}\$this->$field,\n"
    done

    echo -e "$output"
}

# Main loop: Get all tables from schema
tables=$(jq -r '.[].table_name' "$SCHEMA_FILE")

for table in $tables; do
    read -r namespace subdir <<< "$(get_namespace_and_dir "$table")"
    class_name=$(to_camel_case "$table")

    # Determine file path
    mkdir -p "$ROOT_DIR/$subdir"
    file_path="$ROOT_DIR/$subdir/${class_name%.php}.php"  # Ensure no .php in class_name

    # Generate PHP content
    toarray_content=$(generate_toarray "$table")

    php_content="<?php

namespace $namespace;

use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class $class_name extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request \$request): array
    {
        return [
$toarray_content        ];
    }
}
"

    # Write or overwrite the file
    echo "$php_content" > "$file_path"
    echo "Generated: $file_path"
done