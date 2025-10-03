#!/bin/bash

# Shell script to generate PHP validation requests based on JSON schema
# Usage: ./generate_validate.sh
# Assumes schema.json is in auto_script/dataSchema/
# Will create/overwrite files in app/Http/Requests/...

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
mkdir -p "$ROOT_PATH/app/Http/Requests/Master"
mkdir -p "$ROOT_PATH/app/Http/Requests/Management"
mkdir -p "$ROOT_PATH/app/Http/Requests/History/Master"
mkdir -p "$ROOT_PATH/app/Http/Requests/History/Management"

# Get list of tables (modified to handle array structure)
TABLES=$(jq -r '.[].table_name' "$JSON_FILE")

for TABLE in $TABLES; do
    # Determine subpath and directory based on suffix
    if [[ $TABLE == *_mst_hist ]]; then
        SUBPATH="History\\\\Master"
        BASE_DIR="$ROOT_PATH/app/Http/Requests/History/Master"
    elif [[ $TABLE == *_mgmt_hist ]]; then
        SUBPATH="History\\\\Management"
        BASE_DIR="$ROOT_PATH/app/Http/Requests/History/Management"
    elif [[ $TABLE == *_mgmt ]]; then
        SUBPATH="Management"
        BASE_DIR="$ROOT_PATH/app/Http/Requests/Management"
    elif [[ $TABLE == *_mst ]]; then
        SUBPATH="Master"
        BASE_DIR="$ROOT_PATH/app/Http/Requests/Master"
    else
        echo "Skipping $TABLE: No matching suffix (_mst, _mgmt, _mst_hist, _mgmt_hist)"
        continue
    fi

    # Generate camel case class name for table: Convert snake_case to CamelCase
    CLASS_NAME=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); }')

    # Create subdirectory for the table
    TABLE_DIR="$BASE_DIR/$CLASS_NAME"
    mkdir -p "$TABLE_DIR"

    # Get list of fields (modified to handle array structure)
    TABLE_INDEX=$(jq -r "map(.table_name == \"$TABLE\") | index(true)" "$JSON_FILE")
    FIELDS=$(jq -r ".[$TABLE_INDEX].columns[].name" "$JSON_FILE")

    # Check if junction: no 'id' and >=2 *_ (mst|mgmt|...) _id
    HAS_ID=0
    ID_FIELDS=()
    for FIELD in $FIELDS; do
        if [[ $FIELD == "id" ]]; then
            HAS_ID=1
        fi
        if [[ $FIELD =~ _((mst|mgmt|mst_hist|mgmt_hist))_id$ ]]; then
            ID_FIELDS+=("$FIELD")
        fi
    done

    NUM_ID_FIELDS=${#ID_FIELDS[@]}
    if [[ $HAS_ID == 0 && $NUM_ID_FIELDS -ge 2 ]]; then
        IS_JUNCTION=1
    else
        IS_JUNCTION=0
    fi

    # Get required fields - assuming non-nullable fields are required
    REQUIRED=$(jq -r ".[$TABLE_INDEX].columns[] | select(.nullable == false) | .name" "$JSON_FILE")

    # Function to get type for field
    get_type() {
        field_name=$1
        jq -r ".[$TABLE_INDEX].columns[] | select(.name == \$field_name) | .type" --arg field_name "$field_name" "$JSON_FILE"
    }

    # Function to get format for field (simplified - determine based on type)
    get_format() {
        local field_type=$(get_type "$1")
        if [[ $field_type == "timestamp" || $field_type == "date" ]]; then
            echo "date-time"
        else
            echo ""
        fi
    }

    # Function to get maxLength for field
    get_maxlength() {
        field_name=$1
        local size=$(jq -r ".[$TABLE_INDEX].columns[] | select(.name == \$field_name) | .size" --arg field_name "$field_name" "$JSON_FILE")
        if [[ $size == "null" ]]; then
            echo ""
        else
            echo "$size"
        fi
    }

    # Function to get rules for a field, for a specific request type (list, store, update, etc.)
    # Params: field, request_type (list/store/update), is_required (0/1)
    get_rules() {
        local field="$1"
        local req_type="$2"
        local is_req="$3"
        local rules=()
        local type=$(get_type "$field")
        local fmt=$(get_format "$field")
        local max_len=$(get_maxlength "$field")

        if [[ $req_type == "list" ]]; then
            rules+=("'nullable',")
        elif [[ $is_req == 1 ]]; then
            rules+=("'required',")
        fi

        case "$field" in
            email)
                rules+=("'email:rfc,dns',")
                rules+=("'min:' . CommonVal::MIN_VARCHAR,")
                rules+=("'max:' . CommonVal::MAX_EMAIL,")
                rules+=("Rule::unique($CLASS_NAME::class, 'email'),")
                ;;
            phone_number)
                rules+=("'string',")
                rules+=("'min:' . CommonVal::MIN_VARCHAR,")
                rules+=("'max:' . CommonVal::MAX_PHONE_NUMBER,")
                ;;
            gender)
                rules+=("new Enum(Gender::class),")
                ;;
            status)
                rules+=("new Enum(StatusEnum::class),")
                ;;
            birth)
                rules+=("'date_format:' . CommonVal::DATE_FORMAT,")
                rules+=("'after_or_equal:' . CommonVal::MIN_DATE,")
                rules+=("'before_or_equal:' . CommonVal::MAX_DATE,")
                ;;
            is_active)
                rules+=("new Enum(IsActive::class),")
                ;;
            is_delete)
                rules+=("new Enum(IsDelete::class),")
                ;;
            *)
                if [[ $type == "integer" ]]; then
                    rules+=("'integer',")
                    rules+=("'min:' . CommonVal::MIN_INTEGER,")
                    rules+=("'max:' . CommonVal::MAX_INTEGER,")
                elif [[ $type == "character varying" || $type == "text" || $type == "varchar" || $type == "string" ]]; then
                    rules+=("'string',")
                    rules+=("'min:' . CommonVal::MIN_VARCHAR,")
                    if [[ -n "$max_len" ]]; then
                        rules+=("'max:$max_len',")
                    else
                        rules+=("'max:' . CommonVal::MAX_VARCHAR,")
                    fi
                elif [[ $type == "boolean" ]]; then
                    rules+=("new Enum(IsActive::class),")
                fi
                if [[ $fmt == "date-time" ]]; then
                    rules+=("'date_format:' . CommonVal::DATE_FORMAT,")
                    rules+=("'after_or_equal:' . CommonVal::MIN_DATE,")
                    rules+=("'before_or_equal:' . CommonVal::MAX_DATE,")
                fi
                ;;
        esac

        # If foreign key and (store or update), add Rule::exists
        if [[ $field =~ _((mst|mgmt|mst_hist|mgmt_hist))_id$ ]] && [[ $req_type == "store" || $req_type == "update" ]]; then
            local suffix=$(echo "$field" | sed -E 's/.*_((mst|mgmt|mst_hist|mgmt_hist))_id$/\1/')
            local foreign_table=$(echo "$field" | sed 's/_id$//')
            local FOREIGN_CLASS=$(echo "$foreign_table" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); }')
            rules=("'required'" "'integer'" "'min:' . CommonVal::MIN_INTEGER" "'max:' . CommonVal::MAX_INTEGER" "Rule::exists($FOREIGN_CLASS::class, 'id')")
        fi

        echo "${rules[*]}"
    }

    # Collect uses for enums and models
    ENUM_USES=()
    MODEL_USES=()
    for FIELD in $FIELDS; do
        case "$FIELD" in
            gender)
                ENUM_USES+=("use App\\\\Enums\\\\Gender;")
                ;;
            status)
                ENUM_USES+=("use App\\\\Enums\\\\StatusEnum;")
                ;;
            is_active)
                ENUM_USES+=("use App\\\\Enums\\\\IsActive;")
                ;;
            is_delete)
                ENUM_USES+=("use App\\\\Enums\\\\IsDelete;")
                ;;
        esac
        if [[ $FIELD =~ _((mst|mgmt|mst_hist|mgmt_hist))_id$ ]] ; then
            suffix=$(echo "$FIELD" | sed -E 's/.*_((mst|mgmt|mst_hist|mgmt_hist))_id$/\1/')
            foreign_table=$(echo "$FIELD" | sed 's/_id$//')
            FOREIGN_CLASS=$(echo "$foreign_table" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); }')
            MODEL_SUBPATH=""
            if [[ $suffix == "mst_hist" ]]; then
                MODEL_SUBPATH="History\\\\Master"
            elif [[ $suffix == "mgmt_hist" ]]; then
                MODEL_SUBPATH="History\\\\Management"
            elif [[ $suffix == "mgmt" ]]; then
                MODEL_SUBPATH="Management"
            elif [[ $suffix == "mst" ]]; then
                MODEL_SUBPATH="Master"
            fi
            MODEL_USES+=("use App\\Models\\$MODEL_SUBPATH\\$FOREIGN_CLASS;")
        fi
    done

    # Unique enums and models - using a safer approach
    readarray -t TEMP_ENUM_USES < <(printf '%s\n' "${ENUM_USES[@]}" | sort -u)
    ENUM_USES=("${TEMP_ENUM_USES[@]}")
    
    readarray -t TEMP_MODEL_USES < <(printf '%s\n' "${MODEL_USES[@]}" | sort -u)
    MODEL_USES=("${TEMP_MODEL_USES[@]}")

    # For junction, ID rules for update
    if [[ $IS_JUNCTION == 1 ]]; then
        ID_RULES=()
        for ID_FIELD in "${ID_FIELDS[@]}"; do
            ID_RULES+=("'insert.*.$ID_FIELD' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],")
            ID_RULES+=("'delete.*.$ID_FIELD' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],")
        done
    fi

    # Generate files
    if [[ $IS_JUNCTION == 1 ]]; then
        REQUEST_TYPES=("List" "Update")
    else
        REQUEST_TYPES=("Delete" "List" "Store" "Update")
    fi

    for REQ_TYPE in "${REQUEST_TYPES[@]}"; do
        FULL_CLASS="${REQ_TYPE}${CLASS_NAME}Request"
        FILE="$TABLE_DIR/${FULL_CLASS}.php"

        # Add own model use for delete and update if not junction
        OWN_MODEL_USE=""
        if [[ ($REQ_TYPE == "Delete" || $REQ_TYPE == "Update") && $IS_JUNCTION == 0 ]]; then
            OWN_MODEL_USE="use App\\Models\\$SUBPATH\\$CLASS_NAME;"
        fi

        # Prepare use statements
        USE_STATEMENTS="use Illuminate\\Foundation\\Http\\FormRequest;\nuse App\\Constants\\CommonVal;\nuse Illuminate\\Validation\\Rule;"
        
        # Add own model use if needed
        if [[ -n "$OWN_MODEL_USE" ]]; then
            USE_STATEMENTS="$USE_STATEMENTS\n$OWN_MODEL_USE"
        fi
        
        # Add enum and model use statements
        for enum_use in "${ENUM_USES[@]}"; do
            USE_STATEMENTS="$USE_STATEMENTS\n$enum_use"
        done
        for model_use in "${MODEL_USES[@]}"; do
            USE_STATEMENTS="$USE_STATEMENTS\n$model_use"
        done
        
        cat <<EOF > "$FILE"
<?php

namespace App\\Http\\Requests\\$SUBPATH\\$CLASS_NAME;

$(echo -e "$USE_STATEMENTS")

class $FULL_CLASS extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
EOF

        if [[ $REQ_TYPE == "Delete" ]]; then
            cat <<EOF >> "$FILE"
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_PHONE_NUMBER, Rule::exists($CLASS_NAME::class, 'id')],
EOF
        elif [[ $REQ_TYPE == "List" ]]; then
            for FIELD in $FIELDS; do
                if [[ $FIELD == "id" || $FIELD == "created_at" || $FIELD == "updated_at" || $FIELD == *"token"* || $FIELD == "email_verified_at" ]]; then
                    continue
                fi
                RULES=$(get_rules "$FIELD" "list" 0)
                echo "            '$FIELD' => [$RULES]," >> "$FILE"
            done
            cat <<EOF >> "$FILE"
            'from_date' => [
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
EOF
        elif [[ $IS_JUNCTION == 1 && $REQ_TYPE == "Update" ]]; then
            echo "            'insert' => ['nullable', 'array']," >> "$FILE"
            echo "            'insert.*' => 'array'," >> "$FILE"
            for ID_RULE in "${ID_RULES[@]}"; do
                if [[ $ID_RULE == *insert* ]]; then
                    echo "            $ID_RULE" >> "$FILE"
                fi
            done
            echo "            'delete' => ['nullable', 'array']," >> "$FILE"
            echo "            'delete.*' => 'array'," >> "$FILE"
            for ID_RULE in "${ID_RULES[@]}"; do
                if [[ $ID_RULE == *delete* ]]; then
                    echo "            $ID_RULE" >> "$FILE"
                fi
            done
        else # Store or Update for normal
            for FIELD in $FIELDS; do
                if [[ $FIELD == "id" ]]; then
                    if [[ $REQ_TYPE == "Update" ]]; then
                        echo "            'id' => ['required', 'integer', 'min:1', Rule::exists($CLASS_NAME::class, 'id')]," >> "$FILE"
                    fi
                    continue
                fi
                if [[ $FIELD == "created_at" || $FIELD == "updated_at" || $FIELD == *"token"* || $FIELD == "email_verified_at" ]]; then
                    continue
                fi
                is_req=0
                for req in $REQUIRED; do
                    if [[ $req == $FIELD ]]; then
                        is_req=1
                        break
                    fi
                done
                RULES=$(get_rules "$FIELD" "${REQ_TYPE,,}" "$is_req")
                echo "            '$FIELD' => [$RULES]," >> "$FILE"
            done
        fi

        cat <<EOF >> "$FILE"
        ];
    }

    public function attributes(): array
    {
        return [
EOF
        if [[ $REQ_TYPE == "Delete" ]]; then
            echo "            'ids' => __('messages.ids')," >> "$FILE"
            echo "            'ids.*' => __('messages.ids')," >> "$FILE"
        else
            for FIELD in $FIELDS; do
                if [[ $FIELD == "id" || $FIELD == "created_at" || $FIELD == "updated_at" || $FIELD == *"token"* || $FIELD == "email_verified_at" ]]; then
                    continue
                fi
                echo "            '$FIELD' => __('messages.$FIELD')," >> "$FILE"
            done
            if [[ $REQ_TYPE == "List" ]]; then
                echo "            'from_date' => __('messages.from_date')," >> "$FILE"
                echo "            'to_date' => __('messages.to_date')," >> "$FILE"
            fi
            if [[ $IS_JUNCTION == 1 && $REQ_TYPE == "Update" ]]; then
                for ID_FIELD in "${ID_FIELDS[@]}"; do
                    echo "            'insert.*.$ID_FIELD' => __('messages.$ID_FIELD')," >> "$FILE"
                    echo "            'delete.*.$ID_FIELD' => __('messages.$ID_FIELD')," >> "$FILE"
                done
            fi
        fi
        cat <<EOF >> "$FILE"
        ];
    }
}
EOF

        echo "Generated/Overwritten: $FILE"
    done
done