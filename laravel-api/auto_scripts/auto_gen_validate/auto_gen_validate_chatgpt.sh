#!/usr/bin/env bash
# generate_validators.sh
# Usage:
#   ./generate_validators.sh [schema_path] [output_base_dir]
# Defaults:
#   schema_path = ./database/schema/pgsql-schema.json
#   output_base_dir = ./generated_validators
#
# Yêu cầu: jq phải được cài (apt install jq hoặc brew install jq)

set -euo pipefail

SCHEMA_PATH="${1:-./database/schema/pgsql-schema.json}"
OUT_BASE="${2:-./generated_validators}"

if ! command -v jq >/dev/null 2>&1; then
  echo "ERROR: 'jq' is required. Install it (apt, yum, brew, ...)." >&2
  exit 2
fi

if [ ! -f "$SCHEMA_PATH" ]; then
  echo "ERROR: schema file not found at: $SCHEMA_PATH" >&2
  exit 3
fi

mkdir -p "$OUT_BASE"

# helper: snake_case -> PascalCase
to_pascal() {
  local s="$1"
  # remove leading/trailing underscores, split by _ and capitalize
  IFS='_' read -ra parts <<< "${s}"
  local out=""
  for p in "${parts[@]}"; do
    if [ -n "$p" ]; then
      out="${out}$(tr '[:lower:]' '[:upper:]' <<< "${p:0:1}")${p:1}"
    fi
  done
  echo "$out"
}

# decide folder by table suffix per test.php spec
get_scope_dir() {
  local table="$1"
  if [[ "$table" =~ _mgmt_hist$ ]]; then
    echo "Management/Master"
  elif [[ "$table" =~ _mst_hist$ ]]; then
    echo "History/Master"
  elif [[ "$table" =~ _mgmt$ ]]; then
    echo "Management"
  elif [[ "$table" =~ _mst$ ]]; then
    echo "Master"
  else
    # default generic place
    echo "Other"
  fi
}

# map a single column to a PHP validator rule array (string)
map_column_rules() {
  local col="$1"
  local type="$2"
  local maxlen="$3"
  local format="$4"

  # Normalize
  type="${type:-}"
  maxlen="${maxlen:-}"
  format="${format:-}"

  # If column name ends with _id -> integer (likely foreign key)
  if [[ "$col" =~ _id$ ]]; then
    echo "['nullable','integer']"
    return
  fi

  case "$type" in
    integer)
      echo "['nullable','integer']"
      ;;
    boolean)
      echo "['nullable','boolean']"
      ;;
    string)
      # specific name heuristics
      if [[ "$col" =~ email ]]; then
        if [ -n "$maxlen" ] && [ "$maxlen" -gt 0 ]; then
          echo "['nullable','email','max:${maxlen}']"
        else
          echo "['nullable','email']"
        fi
      elif [[ "$col" =~ slug ]]; then
        if [ -n "$maxlen" ] && [ "$maxlen" -gt 0 ]; then
          echo "['nullable','string','max:${maxlen}','regex:/^[a-z0-9-]+$/']"
        else
          echo "['nullable','string','regex:/^[a-z0-9-]+$/']"
        fi
      elif [[ "$col" =~ phone|tel|mobile ]]; then
        echo "['nullable','string','max:${maxlen:-255}','regex:/^[0-9+\\-\\s()]+$/']"
      else
        if [ -n "$maxlen" ] && [ "$maxlen" -gt 0 ]; then
          echo "['nullable','string','max:${maxlen}']"
        else
          echo "['nullable','string']"
        fi
      fi
      ;;
    *)
      # check format
      if [[ "$format" == "date-time" ]]; then
        # follow the example in test.php using CommonVal constants
        # for to_date fields include after:from_date
        if [[ "$col" == "to_date" ]]; then
          echo "['nullable','date_format:' . CommonVal::DATE_FORMAT,'after_or_equal:' . CommonVal::MIN_DATE,'before_or_equal:' . CommonVal::MAX_DATE,'after:from_date']"
        else
          echo "['nullable','date_format:' . CommonVal::DATE_FORMAT,'after_or_equal:' . CommonVal::MIN_DATE,'before_or_equal:' . CommonVal::MAX_DATE]"
        fi
      else
        # fallback: string
        if [ -n "$maxlen" ] && [ "$maxlen" -gt 0 ]; then
          echo "['nullable','string','max:${maxlen}']"
        else
          echo "['nullable','string']"
        fi
      fi
      ;;
  esac
}

# Read top-level properties (tables)
tables_json=$(jq -r '(.properties // {}) | keys[]' "$SCHEMA_PATH")

for table in $tables_json; do
  # read columns object
  props_path=".properties[\"$table\"].properties"
  # jq -r returns lines of "col|type|maxLength|format" to parse easily
  columns_info=$(jq -r "
    $props_path
    // {} |
    to_entries[] |
    [
      .key,
      (.value.type // \"\"),
      (.value.maxLength // \"\"),
      (.value.format // \"\")
    ] | @tsv
  " "$SCHEMA_PATH" || true)

  # count *_id columns for pivot detection (only when table suffix _mst or _mgmt)
  id_count=$(jq -r "($props_path // {}) | keys[] | select(test(\"_id$\"))" "$SCHEMA_PATH" 2>/dev/null | wc -l || true)
  dir_rel="$(get_scope_dir "$table")"

  out_dir="${OUT_BASE}/${dir_rel}"
  mkdir -p "$out_dir"

  class_base="$(to_pascal "$table")"
  class_name="${class_base}Validator"
  file_path="${out_dir}/${class_name}.php"

  echo "Generating $file_path ..."

  # Build rules array lines
  rules_lines=""
  attributes_lines=""

  # If pivot rule: table ends with _mst or _mgmt AND at least 2 *_id columns -> create simple pk/id rules
  if { [[ "$table" =~ _mst$ ]] || [[ "$table" =~ _mgmt$ ]]; } && [ "$id_count" -ge 2 ]; then
    # For pivot table, only validate *_id fields
    jq -r "($props_path // {}) | to_entries[] | select(.key|test(\"_id$\")) | .key" "$SCHEMA_PATH" | while IFS= read -r k; do
      rules="$(map_column_rules "$k" "integer" "" "")"
      # convert "['a','b']" to PHP array syntax '["a","b"]' in final output - we will produce PHP array by printing pieces
      rules_lines="${rules_lines}        '${k}' => ${rules},\n"
      attributes_lines="${attributes_lines}            '${k}' => __('message.${k}'),\n"
    done
  else
    # Normal table: iterate all columns and map rules
    while IFS=$'\t' read -r col coltype maxlen format; do
      # ensure non-empty column name
      if [ -z "$col" ]; then continue; fi
      # map types: jq sometimes returns "integer" or "string" or "boolean" etc.
      mapped=$(map_column_rules "$col" "$coltype" "$maxlen" "$format")
      rules_lines="${rules_lines}        '${col}' => ${mapped},\n"
      attributes_lines="${attributes_lines}            '${col}' => __('message.${col}'),\n"
    done <<< "$columns_info"
  fi

  # Remove trailing comma/newline in safe manner when printing in heredoc not necessary

  # Write the PHP validator file
  cat > "$file_path" <<PHP
<?php

namespace App\Validators\\${dir_rel//\//\\};

use App\Constants\CommonVal;

/**
 * Auto-generated validator for table: ${table}
 * Scope folder: ${dir_rel}
 * Class: ${class_name}
 *
 * Rules are inferred from the JSON schema at: ${SCHEMA_PATH}
 *
 * NOTE:
 *  - date fields use CommonVal::DATE_FORMAT, MIN_DATE, MAX_DATE per test.php guidance.
 *  - foreign keys (*_id) are validated as integers (nullable) by default.
 *  - For pivot/intermediate tables (suffix _mst or _mgmt and >=2 *_id columns),
 *    we only generate integer rules for the *_id columns.
 */
class ${class_name}
{
    /**
     * Get validation rules
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
$(printf "%b" "${rules_lines}")
        ];
    }

    /**
     * Get custom attribute names for validator errors
     *
     * @return array
     */
    public static function attributes(): array
    {
        return [
$(printf "%b" "${attributes_lines}")
        ];
    }
}

PHP

done

echo "Done. Generated validators are in: $OUT_BASE"
echo "Tip: adjust namespace, CommonVal path or rule details if your project conventions differ."
