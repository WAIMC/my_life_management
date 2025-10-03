#!/bin/bash

# Shell script to generate Laravel API routes based on JSON schema
# Usage: ./generate_routes.sh
# Assumes pgsql-schema.json is in the current directory
# Generates a file: routes/api_generated.php (include it in api.php)

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

OUTPUT_FILE="$ROOT_PATH/routes/api_generated.php"

# Clear or create output file
echo "<?php" > "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"
echo "use Illuminate\Support\Facades\Route;" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

# Get list of tables
TABLES=$(jq -r '.[].table_name' "$JSON_FILE")

for TABLE in $TABLES; do
    # Determine subpath based on suffix
    if [[ $TABLE == *_mst_hist ]]; then
        SUBPATH="History\\Master"
    elif [[ $TABLE == *_mgmt_hist ]]; then
        SUBPATH="History\\Management"
    elif [[ $TABLE == *_mgmt ]]; then
        SUBPATH="Management"
    elif [[ $TABLE == *_mst ]]; then
        SUBPATH="Master"
    else
        echo "Skipping $TABLE: No matching suffix"
        continue
    fi

    # Generate controller class name
    CONTROLLER_CLASS=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } END{print "Controller"}')

    # Route name: use full table name with hyphens instead of underscores
    ROUTE_NAME=$(echo "$TABLE" | tr '_' '-')  # e.g., admin_mst -> admin-mst

    # Get fields to check if junction
    TABLE_INDEX=$(jq -r "map(.table_name == \"$TABLE\") | index(true)" "$JSON_FILE")
    FIELDS=$(jq -r ".[$TABLE_INDEX].columns[].name" "$JSON_FILE")
    HAS_ID=0
    ID_COUNT=0
    for FIELD in $FIELDS; do
        if [[ $FIELD == "id" ]]; then HAS_ID=1; fi
        if [[ $FIELD =~ _id$ ]]; then ((ID_COUNT++)); fi
    done
    if [[ $HAS_ID == 0 && $ID_COUNT -ge 2 ]]; then
        IS_JUNCTION=1
    else
        IS_JUNCTION=0
    fi

    # Generate routes
    echo "// Routes for $TABLE" >> "$OUTPUT_FILE"
    if [[ $IS_JUNCTION == 0 ]]; then
        # Normal table: full CRUD
        echo "Route::apiResource('$ROUTE_NAME', \\App\\Http\\Controllers\\$SUBPATH\\$CONTROLLER_CLASS::class);" >> "$OUTPUT_FILE"
    else
        # Junction: list (GET) and update (PATCH or PUT)
        echo "Route::get('$ROUTE_NAME', [\\App\\Http\\Controllers\\$SUBPATH\\$CONTROLLER_CLASS::class, 'list']);" >> "$OUTPUT_FILE"
        echo "Route::put('$ROUTE_NAME', [\\App\\Http\\Controllers\\$SUBPATH\\$CONTROLLER_CLASS::class, 'update']);  // or put if preferred" >> "$OUTPUT_FILE"
    fi
    echo "" >> "$OUTPUT_FILE"
done

echo "Generated routes in $OUTPUT_FILE. Include it in routes/api.php with: require __DIR__.'/api_generated.php';"