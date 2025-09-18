#!/bin/bash

# Script to generate Laravel CRUD prompts from pgsql-schema.json
# Requires: jq (for JSON parsing), python3

SCHEMA_FILE="$(dirname "$0")/database/schema/pgsql-schema.json"
OUTPUT_FILE="laravel_crud_prompts.json"

if [ ! -f "$SCHEMA_FILE" ]; then
  echo "Error: $SCHEMA_FILE not found!"
  exit 1
fi

# Use Python to parse and generate prompts (since bash + jq is limited for complex logic)
python3 - "$SCHEMA_FILE" "$OUTPUT_FILE" << 'EOF'
import json
import sys
import os

schema_file_path = sys.argv[1]
output_file_path = sys.argv[2]

# Read schema
with open(schema_file_path, "r") as f:
    schema_data = json.load(f)

def detect_scope(table_name):
    if "_mst" in table_name:
        return "master"
    elif "_mgmt" in table_name:
        return "management"
    elif "_hist" in table_name:
        return "history"
    return "unknown"

def is_foreign_key(col_name):
    return col_name.endswith("_id") and col_name != "id"

def get_fk_target(col_name, all_tables):
    base = col_name.replace("_id", "")
    # First, look for an exact match.
    if base in all_tables:
        return base
    
    # If no exact match, try to find a table that starts with the base name
    # and has a standard suffix, prioritizing _mst, then _mgmt.
    possible_tables = [t for t in all_tables if t.startswith(base + '_')]
    if possible_tables:
        for suffix in ['_mst', '_mgmt', '_hist']:
            for t in possible_tables:
                if t == base + suffix:
                    return t
        # If no specific suffix match, return the first possible table.
        return possible_tables[0]
    return None

def build_column_line(col_name, prop, required, is_fk, fk_target):
    field_type = prop.get("type", "unknown")
    max_len = prop.get("maxLength", "")
    default = prop.get("default", "")
    is_null = "NULL" if col_name not in required else "NOT NULL"
    fk_note = f" (foreign key to {fk_target})" if is_fk and fk_target else ""
    
    if field_type == "string":
        len_str = f"({max_len})" if max_len else ""
        col_str = f'"{col_name}" varchar{len_str} {is_null}{fk_note},'
    elif field_type == "integer":
        col_str = f'"{col_name}" int4 {is_null}{fk_note},'
    elif field_type == "boolean":
        default_str = f" DEFAULT {default}" if default is not False else ""
        col_str = f'"{col_name}" bool {is_null}{default_str}{fk_note},'
    elif "date-time" in str(prop.get("format", "")):
        col_str = f'"{col_name}" timestamp(0) {is_null}{fk_note},'
    else:
        col_str = f'"{col_name}" {field_type} {is_null}{fk_note},'
    
    if default and field_type != "boolean":
        col_str = col_str.replace(is_null, f"DEFAULT {default} {is_null}")
    
    return f"    {col_str}"

all_tables = list(schema_data["properties"].keys())
prompts = {}
for table_name, table_data in schema_data["properties"].items():
    if table_data.get("type") == "object":
        properties = table_data.get("properties", {})
        required = table_data.get("required", [])
        columns = []
        for col, prop in properties.items():
            is_fk = is_foreign_key(col)
            fk_target = get_fk_target(col, all_tables) if is_fk else None
            columns.append(build_column_line(col, prop, required, is_fk, fk_target))
        
        column_block = "\n".join(columns)
        scope = detect_scope(table_name)
        prompt = f"""Generate a complete CRUD API module from the following migration file content:

- Scope: {scope}

- Table: {table_name}

- Column 
{column_block}
"""
        prompts[table_name] = {
            "scope": scope,
            "prompt": prompt
        }

with open(output_file_path, "w") as f:
    json.dump(prompts, f, indent=2)

print(f"Generated prompts in {os.path.abspath(output_file_path)}")
EOF

echo "Script completed. Check $OUTPUT_FILE for prompts."