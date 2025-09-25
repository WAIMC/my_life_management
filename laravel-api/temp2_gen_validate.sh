#!/bin/bash
# --------------------------------------------------------------------------------
# Laravel FormRequest Generator
#
# This script automates the creation of Laravel FormRequest validation files based on
# a predefined JSON database schema. It dynamically generates Store, Update, List, and
# Delete requests with appropriate validation rules and folder structures.
#
# Usage:./generate_requests.sh <path_to_schema_file.json>
#
# Dependencies: 'jq' must be installed.
# --------------------------------------------------------------------------------

# --- Configuration Variables ---
REQUEST_BASE_PATH="App/Http/Requests"
MODEL_BASE_PATH="App/Models"
COMMONVAL_NAMESPACE="App/Constants"

# Placeholder for the input schema file
SCHEMA_FILE=$1

# --- Helper Functions ---

# Function to convert snake_case to PascalCase
function pascal_case() {
    echo "$1" | awk -F'_' '{for(i=1;i<=NF;i++){$i=toupper(substr($i,1,1))tolower(substr($i,2))}}1' OFS=''
}

# Function to get path and namespace based on table suffix
function get_path_and_namespace() {
    local table_name=$1
    if [[ "$table_name" =~ _mst$ ]]; then
        echo "Master"
    elif [[ "$table_name" =~ _mgmt$ ]]; then
        echo "Management"
    elif [[ "$table_name" =~ _mgmt_hist$ ]]; then
        echo "History/Management"
    elif [[ "$table_name" =~ _mst_hist$ ]]; then
        echo "History/Master"
    else
        echo "" # Default or error case
    fi
}

# Function to determine if a table is an intermediate/pivot table
function is_pivot_table() {
    local columns_json=$1
    local id_columns_count=0
    for col_name in $(echo "$columns_json" | jq -r '. |.name'); do
        if [[ "$col_name" =~ _id$ ]]; then
            ((id_columns_count++))
        fi
    done
    [[ $id_columns_count -eq 2 ]]
}

# Function to generate validation rules
function generate_rules() {
    local columns_json=$1
    local is_update_or_list=$2
    local is_list_only=$3
    local rules=""
    
    # Add common rules for List requests
    if [[ "$is_list_only" == "true" ]]; then
        rules+="\t\t\t'from_date' =>,\n"
        rules+="\t\t\t'to_date' =>,\n"
    fi

    for col in $(echo "$columns_json" | jq -c '.'); do
        local col_name=$(echo "$col" | jq -r '.name')
        local data_type=$(echo "$col" | jq -r '.data_type')
        local nullable=$(echo "$col" | jq -r '.nullable')
        local is_pk=$(echo "$col" | jq -r '.is_primary_key')
        
        # Skip primary key and timestamps
        if [[ "$is_pk" == "true" ]] |

| [[ "$col_name" == "created_at" ]] |
| [[ "$col_name" == "updated_at" ]]; then
            continue
        fi

        rules+="\t\t\t'$col_name' => [\n"

        if [[ "$nullable" == "true" ]] |

| [[ "$is_update_or_list" == "true" ]]; then
            rules+="\t\t\t\t'nullable',\n"
        fi

        if [[ "$is_update_or_list" == "true" ]]; then
            rules+="\t\t\t\t'sometimes',\n"
        elif [[ "$nullable" == "false" ]]; then
            rules+="\t\t\t\t'required',\n"
        fi

        case "$data_type" in
            "VARCHAR")
                rules+="\t\t\t\t'string',\n"
                if [[ "$col_name" =~ _email$ ]]; then
                    rules+="\t\t\t\t'email',\n"
                    rules+="\t\t\t\t'max:'. CommonVal::MAX_EMAIL,\n"
                elif [[ "$col_name" =~ _phone_number$ ]]; then
                    rules+="\t\t\t\t'string',\n"
                    rules+="\t\t\t\t'max:'. CommonVal::MAX_PHONE_NUMBER,\n"
                else
                    rules+="\t\t\t\t'max:'. CommonVal::MAX_VARCHAR,\n"
                fi
                ;;
            "TEXT")
                rules+="\t\t\t\t'string',\n"
                ;;
            "INT" | "BIGINT")
                rules+="\t\t\t\t'integer',\n"
                rules+="\t\t\t\t'min:'. CommonVal::MIN_INTEGER,\n"
                rules+="\t\t\t\t'max:'. CommonVal::MAX_INTEGER,\n"
                ;;
            "BOOLEAN")
                rules+="\t\t\t\t'boolean',\n"
                ;;
            "DATE" | "DATETIME")
                rules+="\t\t\t\t'date_format:'. CommonVal::DATE_FORMAT,\n"
                rules+="\t\t\t\t'after_or_equal:'. CommonVal::MIN_DATE,\n"
                rules+="\t\t\t\t'before_or_equal:'. CommonVal::MAX_DATE,\n"
                ;;
            *)
                # Fallback for unknown types
                ;;
        esac

        if [[ "$col_name" =~ _id$ ]]; then
            rules+="\t\t\t\t'exists:table_name,id'\n" # Placeholder, will be replaced later
        fi
        
        rules+="\t\t\t],\n"
    done
    echo -e "$rules"
}

# Function to generate the file content
function generate_file_content() {
    local request_name=$1
    local namespace=$2
    local rules_content=$3
    local model_name=$4
    local is_delete=$5

    local model_path=""
    if [[ "$namespace" == "Master" ]]; then
        model_path="Master"
    elif [[ "$namespace" == "Management" ]]; then
        model_path="Management"
    elif [[ "$namespace" == "Management/Master" ]]; then
        model_path="Management\Master"
    elif [[ "$namespace" == "History/Master" ]]; then
        model_path="History\Master"
    fi
    local use_model="use $MODEL_BASE_PATH\\${model_path//\//\\}\\$model_name;"

    local content=$(cat <<EOF
<?php

namespace $REQUEST_BASE_PATH\\${namespace//\//\\};

use $COMMONVAL_NAMESPACE\CommonVal;
$use_model
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class $request_name extends FormRequest
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
$rules_content
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
            //
        ];
    }
}
EOF
)
    # Handle DeleteRequest special case
    if [[ "$is_delete" == "true" ]]; then
        content=$(echo "$content" | sed "s|//|'ids' => [\n                'required',\n                'array',\n            ],\n            'ids.*' =>,|g")
    fi
    
    echo "$content" > "$REQUEST_BASE_PATH/$namespace/$request_name.php"
}

# --- Main Script Logic ---

if; then
    echo "Error: Schema file '$SCHEMA_FILE' not found!"
    exit 1
fi

echo "Starting FormRequest generation..."

# Read JSON schema file and iterate over each table
jq -c '.' "$SCHEMA_FILE" | while read -r table; do
    TABLE_NAME=$(echo "$table" | jq -r '.name')
    COLUMNS_JSON=$(echo "$table" | jq -c '.columns')
    PASCAL_NAME=$(pascal_case "$TABLE_NAME")
    NAMESPACE_PATH=$(get_path_and_namespace "$TABLE_NAME")
    MODEL_NAME=$(pascal_case "$TABLE_NAME")

    if; then
        echo "Skipping table '$TABLE_NAME' due to unknown suffix."
        continue
    fi
    
    # Create directory if it doesn't exist
    mkdir -p "$REQUEST_BASE_PATH/$NAMESPACE_PATH"
    
    echo "Processing table: $TABLE_NAME -> Model: $MODEL_NAME"

    if is_pivot_table "$COLUMNS_JSON"; then
        echo "  - Identified as a pivot table. Generating List and Update requests."
        # Generate List Request
        RULES=$(generate_rules "$COLUMNS_JSON" false true)
        generate_file_content "${PASCAL_NAME}ListRequest" "$NAMESPACE_PATH" "$RULES" "$MODEL_NAME" false
        # Generate Update Request
        RULES=$(generate_rules "$COLUMNS_JSON" true false)
        generate_file_content "${PASCAL_NAME}UpdateRequest" "$NAMESPACE_PATH" "$RULES" "$MODEL_NAME" false
    else
        echo "  - Identified as a standard table. Generating all four request types."
        # Generate Store Request
        RULES=$(generate_rules "$COLUMNS_JSON" false false)
        generate_file_content "${PASCAL_NAME}StoreRequest" "$NAMESPACE_PATH" "$RULES" "$MODEL_NAME" false
        # Generate Update Request
        RULES=$(generate_rules "$COLUMNS_JSON" true false)
        generate_file_content "${PASCAL_NAME}UpdateRequest" "$NAMESPACE_PATH" "$RULES" "$MODEL_NAME" false
        # Generate List Request
        RULES=$(generate_rules "$COLUMNS_JSON" false true)
        generate_file_content "${PASCAL_NAME}ListRequest" "$NAMESPACE_PATH" "$RULES" "$MODEL_NAME" false
        # Generate Delete Request
        RULES="" # Delete request has specific rules
        generate_file_content "${PASCAL_NAME}DeleteRequest" "$NAMESPACE_PATH" "$RULES" "$MODEL_NAME" true
    fi

done

echo "FormRequest generation complete."