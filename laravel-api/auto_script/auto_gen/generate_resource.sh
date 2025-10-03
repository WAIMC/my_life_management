#!/bin/bash

# File shell script to generate PHP resources based on JSON schema
# Usage: ./generate_resources.sh
# Assumes schema.json is in the auto_script/dataSchema directory
# Will create/overwrite files in App/Http/Resources/...

get_root_path() {
  if [[ -n "$PROJECT_ROOT" ]]; then
    echo "$PROJECT_ROOT"
  else
    # Default: get parent directory of script (assuming script is in auto_scripts/auto_gen)
    cd "$(dirname "$0")/../.." && pwd
  fi
}
ROOT_PATH=$(get_root_path)
JSON_FILE="$ROOT_PATH/auto_script/dataSchema/schema.json"

# Create directories if they don't exist
mkdir -p "$ROOT_PATH/app/Http/Resources/Master"
mkdir -p "$ROOT_PATH/app/Http/Resources/Management"
mkdir -p "$ROOT_PATH/app/Http/Resources/History/Master"
mkdir -p "$ROOT_PATH/app/Http/Resources/History/Management"

# Get list of tables
TABLES=$(jq -r '.[].table_name' "$JSON_FILE")

for TABLE in $TABLES; do
    # Determine subpath and directory based on suffix
    if [[ $TABLE == *_mst_hist ]]; then
        SUBPATH="History\\Master"
        DIR="$ROOT_PATH/app/Http/Resources/History/Master"
    elif [[ $TABLE == *_mgmt_hist ]]; then
        SUBPATH="History\\Management"
        DIR="$ROOT_PATH/app/Http/Resources/History/Management"
    elif [[ $TABLE == *_mgmt ]]; then
        SUBPATH="Management"
        DIR="$ROOT_PATH/app/Http/Resources/Management"
    elif [[ $TABLE == *_mst ]]; then
        SUBPATH="Master"
        DIR="$ROOT_PATH/app/Http/Resources/Master"
    else
        echo "Skipping $TABLE: No matching suffix (_mst, _mgmt, _mst_hist, _mgmt_hist)"
        continue
    fi

    # Generate class name: Convert snake_case to CamelCase and add 'Resource'
    # For hist, include 'Hist' if needed, but based on examples, treat as part of name
    CLASS_NAME=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } END{print "Resource"}')

    # Get list of fields for the table
    FIELDS=$(jq -r ".[] | select(.table_name == \"$TABLE\") | .columns[].name" "$JSON_FILE")

    # Generate the PHP file (overwrite if exists)
    FILE="$DIR/$CLASS_NAME.php"
    echo "<?php" > "$FILE"
    echo "" >> "$FILE"
    echo "namespace App\\Http\\Resources\\$SUBPATH;" >> "$FILE"
    echo "" >> "$FILE"
    echo "use App\\Constants\\CommonVal;" >> "$FILE"
    echo "use Illuminate\\Http\\Request;" >> "$FILE"
    echo "use Illuminate\\Http\\Resources\\Json\\JsonResource;" >> "$FILE"
    echo "" >> "$FILE"
    echo "class $CLASS_NAME extends JsonResource" >> "$FILE"
    echo "{" >> "$FILE"
    echo "    /**" >> "$FILE"
    echo "     * Transform the resource into an array." >> "$FILE"
    echo "     *" >> "$FILE"
    echo "     * @return array<string, mixed>" >> "$FILE"
    echo "     */" >> "$FILE"
    echo "    public function toArray(Request \$request): array" >> "$FILE"
    echo "    {" >> "$FILE"
    echo "        return [" >> "$FILE"

    # Loop through fields and add to return array, excluding specified ones
    for FIELD in $FIELDS; do
        # Exclude rules: created_at (except for _hist tables), %token%, email_verified_at
        EXCLUDE=0
        if [[ $FIELD == *"token"* || $FIELD == "email_verified_at" ]]; then
            EXCLUDE=1
        fi
        if [[ $FIELD == "created_at" && $TABLE != *_hist ]]; then
            EXCLUDE=1
        fi
        if [[ $EXCLUDE == 1 ]]; then
            continue
        fi

        # Get field type from schema - handle field names with special characters
        # Escape the field name for jq
        ESCAPED_FIELD=$(printf '%s' "$FIELD" | sed 's/"/\\"/g')
        TYPE=$(jq -r ".[] | select(.table_name == \"$TABLE\") | .columns[] | select(.name == \"$ESCAPED_FIELD\") | .type // \"string\"" "$JSON_FILE" 2>/dev/null || echo "string")

        # Determine cast/format
        CAST="(string)"
        if [[ $TYPE == "integer" ]]; then
            CAST="(int)"
        elif [[ $TYPE == "boolean" ]]; then
            CAST="(bool)"
        fi  # default string

        # Special formatting for updated_at or created_at in hist tables
        if [[ $FIELD == "updated_at" || ($FIELD == "created_at" && $TABLE == *_hist) ]]; then
            echo "            '$FIELD' => (string)date(CommonVal::DATE_FORMAT, strtotime(\$this->$FIELD))," >> "$FILE"
        elif [[ $TYPE == "timestamp" || $TYPE == "date" ]]; then
            echo "            '$FIELD' => (string)\$this->$FIELD," >> "$FILE"
        else
            echo "            '$FIELD' => $CAST\$this->$FIELD," >> "$FILE"
        fi
    done

    echo "        ];" >> "$FILE"
    echo "    }" >> "$FILE"
    echo "}" >> "$FILE"

    echo "Generated/Overwritten: $FILE"
done
