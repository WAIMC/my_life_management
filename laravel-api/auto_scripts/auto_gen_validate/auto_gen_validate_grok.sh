#!/bin/bash

# File .sh to generate validation request files based on pgsql-schema.json and rules in test.php

JSON_FILE="./database/schema/pgsql-schema.json"

BASE_DIR="app/Http/Requests"

mkdir -p "$BASE_DIR"

# Get all tables
tables=$(jq -r '.properties | keys[]' "$JSON_FILE")

for table in $tables; do
  # Determine suffix and folder
  if [[ $table == *"_mst_hist" ]]; then
    folder="History/Master"
    suffix_type="mst_hist"
  elif [[ $table == *"_mgmt_hist" ]]; then
    folder="History/Management"
    suffix_type="mgmt_hist"
  elif [[ $table == *"_mst" ]]; then
    folder="Master"
    suffix_type="mst"
  elif [[ $table == *"_mgmt" ]]; then
    folder="Management"
    suffix_type="mgmt"
  else
    continue  # Skip if no matching suffix
  fi

  # Convert table to CamelCase name (e.g., admin_mst -> AdminMst)
  name=$(echo "$table" | sed 's/_/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) tolower(substr($i,2))}1' | tr -d ' ')

  # Get columns
  columns=$(jq -r '.properties."'"$table"'".properties | keys[]' "$JSON_FILE")

  # Get required fields as space-separated
  required=$(jq -r '.properties."'"$table"'".required[] // empty' "$JSON_FILE" | tr '\n' ' ')

  # Count *_id columns
  id_count=0
  for col in $columns; do
    if [[ $col == *"_id" ]]; then
      ((id_count++))
    fi
  done

  # Determine if intermediate table
  is_intermediate=0
  if [[ "$suffix_type" == "mst" || "$suffix_type" == "mgmt" ]] && [[ $id_count -eq 2 ]]; then
    is_intermediate=1
  fi

  # Determine file types
  if [[ $is_intermediate -eq 1 ]]; then
    file_types=("List" "Update")
  else
    file_types=("Delete" "List" "Store" "Update")
  fi

  # Create folder if needed
  mkdir -p "$BASE_DIR/$folder"

  for ftype in "${file_types[@]}"; do
    filename="${ftype}${name}Request.php"
    filepath="$BASE_DIR/$folder/$filename"

    # Namespace
    namespace="App\\Http\\Requests\\${folder//\//\\}"

    if [[ $ftype == "Delete" ]]; then
      # Delete template
      model_name="$name"
      table_msg="${table}_id"
      content="<?php

namespace $namespace;

use App\\Constants\\CommonVal;
use App\\Models\\${folder//\//\\}\\${model_name};
use Illuminate\\Foundation\\Http\\FormRequest;
use Illuminate\\Validation\\Rule;

class ${ftype}${name}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => [
                'required',
                'integer',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists(${model_name}::class, 'id')
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'ids' => __('message.${table_msg}'),
            'ids.*' => __('message.${table_msg}'),
        ];
    }

}"
      echo "$content" > "$filepath"
      continue
    fi

    # For other file types: List, Store, Update
    uses="use App\\Constants\\CommonVal;
use Illuminate\\Foundation\\Http\\FormRequest;
use Illuminate\\Validation\\Rule;"

    # Collect enums and models
    declare -A enum_set
    declare -A model_set

    rules=""
    attributes=""

    for col in $columns; do
      # Required or nullable
      if echo "$required" | grep -qw "$col"; then
        req="'required'"
      else
        req="'nullable'"
      fi

      # Get JSON details
      typ=$(jq -r '.properties."'"$table"'".properties."'"$col"'".type // "null"' "$JSON_FILE")
      fmt=$(jq -r '.properties."'"$table"'".properties."'"$col"'".format // "null"' "$JSON_FILE")
      maxl=$(jq -r '.properties."'"$table"'".properties."'"$col"'".maxLength // "null"' "$JSON_FILE")

      # Validates array
      validates=()

      # Priority: column name
      if [[ $col == "email" ]]; then
        validates+=("'email:rfc,dns'")
        validates+=("'min:' . CommonVal::MIN_VARCHAR")
        validates+=("'max:' . CommonVal::MAX_EMAIL")
        validates+=("Rule::unique(${name}::class, 'email')")
        model_set["${folder//\//\\}\\${name}"]=1
      elif [[ $col == "phone_number" ]]; then
        validates+=("'string'")
        validates+=("'min:' . CommonVal::MIN_VARCHAR")
        validates+=("'max:' . CommonVal::MAX_PHONE_NUMBER")
      elif [[ $col == "gender" ]]; then
        validates+=("new Enum(Gender::class)")
        enum_set["Gender"]=1
      elif [[ $col == "status" ]]; then
        validates+=("new Enum(StatusEnum::class)")
        enum_set["StatusEnum"]=1
      elif [[ $col == "birth" ]]; then
        validates+=("'date_format:' . CommonVal::DATE_FORMAT")
        validates+=("'after_or_equal:' . CommonVal::MIN_DATE")
        validates+=("'before_or_equal:' . CommonVal::MAX_DATE")
      elif [[ $col == "is_active" ]]; then
        validates+=("new Enum(IsActive::class)")
        enum_set["IsActive"]=1
      elif [[ $col == "is_delete" ]]; then
        validates+=("new Enum(IsDelete::class)")
        enum_set["IsDelete"]=1
      elif [[ $col == *"_id" ]]; then
        validates+=("'numeric'")
        validates+=("'min:' . CommonVal::MIN_INTEGER")
        validates+=("'max:' . CommonVal::MAX_INTEGER")
        # Determine referenced model
        prefix="${col%_id}"
        ref_model=""
        ref_folder=""
        if jq -e '.properties."'"${prefix}_mst"'"' >/dev/null 2>&1 "$JSON_FILE"; then
          ref_table="${prefix}_mst"
          ref_folder="Master"
          ref_model=$(echo "$ref_table" | sed 's/_/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) tolower(substr($i,2))}1' | tr -d ' ')
        elif jq -e '.properties."'"${prefix}_mgmt"'"' >/dev/null 2>&1 "$JSON_FILE"; then
          ref_table="${prefix}_mgmt"
          ref_folder="Management"
          ref_model=$(echo "$ref_table" | sed 's/_/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) tolower(substr($i,2))}1' | tr -d ' ')
        elif [[ $prefix == "parent" ]]; then
          ref_model="$name"
          ref_folder="$folder"
        fi
        if [[ -n "$ref_model" ]]; then
          validates+=("Rule::exists(${ref_model}::class, 'id')")
          model_set["${ref_folder//\//\\}\\${ref_model}"]=1
        fi
      else
        # Format
        if [[ $fmt == "date-time" ]]; then
          validates+=("'date_format:' . CommonVal::DATE_FORMAT")
          validates+=("'after_or_equal:' . CommonVal::MIN_DATE")
          validates+=("'before_or_equal:' . CommonVal::MAX_DATE")
        # Type
        elif [[ $typ == "integer" ]]; then
          validates+=("'numeric'")
          validates+=("'min:' . CommonVal::MIN_INTEGER")
          validates+=("'max:' . CommonVal::MAX_INTEGER")
        elif [[ $typ == "string" ]]; then
          validates+=("'string'")
          validates+=("'min:' . CommonVal::MIN_VARCHAR")
          if [[ $maxl != "null" ]]; then
            validates+=("'max:${maxl}'")
          else
            validates+=("'max:' . CommonVal::MAX_VARCHAR")
          fi
        elif [[ $typ == "boolean" ]]; then
          validates+=("new Enum(IsActive::class)")
          enum_set["IsActive"]=1
        fi
      fi

      # If validates not empty, add to rules and attributes
      if [[ ${#validates[@]} -gt 0 ]]; then
        rule_line="            '${col}' => [${req}, $(IFS=','; echo "${validates[*]}")],"
        rules+="${rule_line}\n"
        attributes+="            '${col}' => __('message.${col}'),\n"
      fi
    done

    # For ListRequest, add from_date and to_date
    if [[ $ftype == "List" ]]; then
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
            ],\n"
      attributes+="            'from_date' => __('message.from_date'),\n"
      attributes+="            'to_date' => __('message.to_date'),\n"
    fi

    # Build additional uses
    use_str=""
    for enum in "${!enum_set[@]}"; do
      use_str+="use App\\Enums\\${enum};\n"
    done
    for model in "${!model_set[@]}"; do
      use_str+="use App\\Models\\${model};\n"
    done

    if [[ -n "$use_str" ]]; then
      uses+="\n${use_str}"
    fi

    # General template
    content="<?php

namespace $namespace;

${uses}

class ${ftype}${name}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules(): array
    {
        return [
${rules}
        ];
    }

    /**
     * Get custom attribute names for validator errors
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
${attributes}
        ];
    }
}"
    echo "$content" > "$filepath"
  done
done