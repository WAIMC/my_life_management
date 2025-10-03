#!/bin/bash

# Shell script to generate PHP controllers based on JSON schema
# Usage: ./generate_controllers.sh
# Assumes pgsql-schema.json is in the current directory
# Will create/overwrite files in App/Http/Controllers/...

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
mkdir -p "$ROOT_PATH/app/Http/Controllers/Master"
mkdir -p "$ROOT_PATH/app/Http/Controllers/Management"
mkdir -p "$ROOT_PATH/app/Http/Controllers/History/Master"
mkdir -p "$ROOT_PATH/app/Http/Controllers/History/Management"

# Get list of tables
TABLES=$(jq -r '.[].table_name' "$JSON_FILE")

for TABLE in $TABLES; do
    # Determine subpath and directory based on suffix
    if [[ $TABLE == *_mst_hist ]]; then
        SUBPATH="History\\Master"
        DIR="$ROOT_PATH/app/Http/Controllers/History/Master"
    elif [[ $TABLE == *_mgmt_hist ]]; then
        SUBPATH="History\\Management"
        DIR="$ROOT_PATH/app/Http/Controllers/History/Management"
    elif [[ $TABLE == *_mgmt ]]; then
        SUBPATH="Management"
        DIR="$ROOT_PATH/app/Http/Controllers/Management"
    elif [[ $TABLE == *_mst ]]; then
        SUBPATH="Master"
        DIR="$ROOT_PATH/app/Http/Controllers/Master"
    else
        echo "Skipping $TABLE: No matching suffix (_mst, _mgmt, _mst_hist, _mgmt_hist)"
        continue
    fi

    # Generate class name: Convert snake_case to CamelCase + 'Controller'
    CLASS_NAME=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } END{print "Controller"}')

    # Service class name
    SERVICE_CLASS=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } END{print "Service"}')

    # Variable name for service
    VAR_NAME=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) { if(i==1) printf "%s", tolower(substr($i,1,1)) tolower(substr($i,2)); else printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } }')

    # Get list of fields
    TABLE_INDEX=$(jq -r "map(.table_name == \"$TABLE\") | index(true)" "$JSON_FILE")
    FIELDS=$(jq -r ".[$TABLE_INDEX].columns[].name" "$JSON_FILE")

    # Check if junction: no 'id' and >=2 *_id columns
    HAS_ID=0
    ID_FIELDS=0
    for FIELD in $FIELDS; do
        if [[ $FIELD == "id" ]]; then
            HAS_ID=1
        fi
        if [[ $FIELD =~ _((mst|mgmt|mst_hist|mgmt_hist))_id$ ]]; then
            ((ID_FIELDS++))
        fi
    done

    if [[ $HAS_ID == 0 && $ID_FIELDS -ge 2 ]]; then
        IS_JUNCTION=1
    else
        IS_JUNCTION=0
    fi

    # Generate the PHP file (overwrite if exists)
    FILE="$DIR/$CLASS_NAME.php"
    
    # Generate use statements for requests based on whether it's a junction table
    USE_STATEMENTS="use App\\Http\\Requests\\$SUBPATH\\$CLASS_NAME\\List${CLASS_NAME%Controller}Request;"

    if [[ $IS_JUNCTION == 0 ]]; then
        USE_STATEMENTS="$USE_STATEMENTS
use App\\Http\\Requests\\$SUBPATH\\$CLASS_NAME\\Store${CLASS_NAME%Controller}Request;
use App\\Http\\Requests\\$SUBPATH\\$CLASS_NAME\\Update${CLASS_NAME%Controller}Request;
use App\\Http\\Requests\\$SUBPATH\\$CLASS_NAME\\Delete${CLASS_NAME%Controller}Request;"
    else
        USE_STATEMENTS="$USE_STATEMENTS
use App\\Http\\Requests\\$SUBPATH\\$CLASS_NAME\\Update${CLASS_NAME%Controller}Request;"
    fi
    
    # Write the file header with heredoc
    cat <<EOF > "$FILE"
<?php

namespace App\\Http\\Controllers\\$SUBPATH;

$USE_STATEMENTS
use App\\Services\\$SUBPATH\\$SERVICE_CLASS;
use App\\Http\\Controllers\\Controller;
use Illuminate\\Http\\Resources\\Json\\JsonResource;

class $CLASS_NAME extends Controller
{
    public function __construct(
        protected $SERVICE_CLASS \$$VAR_NAME
    )
    {
    }
    
EOF

    # list method
    cat <<EOF >> "$FILE"
    /**
     * ${CLASS_NAME%Controller} list
     *
     * @param List${CLASS_NAME%Controller}Request \$request
     * @return JsonResource
     */
    public function list(List${CLASS_NAME%Controller}Request \$request): JsonResource
    {
        return \$this->$VAR_NAME->list(\$request->all());
    }

EOF

    if [[ $IS_JUNCTION == 0 ]]; then
        # Regular table (non-junction): store, update, delete methods
        cat <<EOF >> "$FILE"
    /**
     * Store ${TABLE//_/ }
     *
     * @param Store${CLASS_NAME%Controller}Request \$request
     * @return int
     */
    public function store(Store${CLASS_NAME%Controller}Request \$request): int
    {
        return \$this->$VAR_NAME->store(\$request->all());
    }

    /**
     * Update ${TABLE//_/ }
     *
     * @param Update${CLASS_NAME%Controller}Request \$request
     * @param string \$id
     * @return int
     */
    public function update(Update${CLASS_NAME%Controller}Request \$request, string \$id): int
    {
        \$payload = \$request->all();
        \$payload['id'] = \$id;

        return \$this->$VAR_NAME->update(\$payload);
    }

    /**
     * Delete ${TABLE//_/ }
     *
     * @param Delete${CLASS_NAME%Controller}Request \$request
     * @return void
     */
    public function delete(Delete${CLASS_NAME%Controller}Request \$request): void
    {
        \$this->$VAR_NAME->delete(\$request->all());
    }
EOF
    else
        # Junction table: only update method
        cat <<EOF >> "$FILE"
    /**
     * Update ${TABLE//_/ }
     *
     * @param Update${CLASS_NAME%Controller}Request \$request
     * @return bool
     */
    public function update(Update${CLASS_NAME%Controller}Request \$request): bool
    {
        return \$this->$VAR_NAME->update(\$request->all());
    }
EOF
    fi

    # Close the class
    echo "}" >> "$FILE"

    echo "Generated/Overwritten: $FILE"
done