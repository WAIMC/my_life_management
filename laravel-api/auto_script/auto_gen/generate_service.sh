#!/bin/bash

# Shell script to generate PHP services based on JSON schema
# Usage: ./generate_services.sh
# Assumes schema.json is in the auto_script/dataSchema directory
# Will create/overwrite files in App/Services/...

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
mkdir -p "$ROOT_PATH/app/Services/Master"
mkdir -p "$ROOT_PATH/app/Services/Management"
mkdir -p "$ROOT_PATH/app/Services/History/Master"
mkdir -p "$ROOT_PATH/app/Services/History/Management"

# Get list of tables
TABLES=$(jq -r '.[].table_name' "$JSON_FILE")

for TABLE in $TABLES; do
    # Determine subpath and directory based on suffix
    if [[ $TABLE == *_mst_hist ]]; then
        SUBPATH="History\\Master"
        DIR="$ROOT_PATH/app/Services/History/Master"
    elif [[ $TABLE == *_mgmt_hist ]]; then
        SUBPATH="History\\Management"
        DIR="$ROOT_PATH/app/Services/History/Management"
    elif [[ $TABLE == *_mgmt ]]; then
        SUBPATH="Management"
        DIR="$ROOT_PATH/app/Services/Management"
    elif [[ $TABLE == *_mst ]]; then
        SUBPATH="Master"
        DIR="$ROOT_PATH/app/Services/Master"
    else
        echo "Skipping $TABLE: No matching suffix (_mst, _mgmt, _mst_hist, _mgmt_hist)"
        continue
    fi

    # Generate class name: Convert snake_case to CamelCase + 'Service'
    CLASS_NAME=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } END{print "Service"}')

    # Variable name: lower first letter
    VAR_NAME=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) { if(i==1) printf "%s", tolower(substr($i,1,1)) tolower(substr($i,2)); else printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } }')

    # Get list of fields
    FIELDS=$(jq -r ".[] | select(.table_name == \"$TABLE\") | .columns[].name" "$JSON_FILE")

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

    # For junction, get short names: replace _suffix_id with _id
    SHORT_NAMES=()
    ITEM_CASTS=""
    if [[ $IS_JUNCTION == 1 ]]; then
        for FIELD in "${ID_FIELDS[@]}"; do
            SHORT="$FIELD"
            SHORT_NAMES+=("$SHORT")
        done
        # Build the map string: '(' . (int)$item['short1'] . ', ' . (int)$item['short2'] . ')' etc.
        ITEM_CASTS=$(for SHORT in "${SHORT_NAMES[@]}"; do echo "(int)\$item['$SHORT']"; done | paste -sd ', ' -)
        ITEM_CASTS="($ITEM_CASTS)"
    fi

    # For junction specifics
    SANS_MST=$(echo "$TABLE" | sed 's/_mst$//')
    CLASS_SANS_MST=$(echo "$SANS_MST" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); }')
    CLASS_WITH_MST=$(echo "$TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); }')
    VAR_ID="${CLASS_WITH_MST}Id"
    ATTR_NAME=$(echo "$SANS_MST" | sed 's/_/ /g' | awk '{for(i=1;i<=NF;i++) printf "%s_", tolower($i); }' | sed 's/_$//')_id
    TABLE_TRANS=$(echo "$TABLE" | sed 's/_/ /g' | awk '{for(i=1;i<=NF;i++) printf "%s_", tolower($i); }' | sed 's/_$//')
    GET_SANS_ID="get${CLASS_SANS_MST}Id"
    GET_WITH_ID="get${CLASS_WITH_MST}Id"

    # Check if contains 'role' for isMyRole
    if [[ $TABLE == *role* ]]; then
        HAS_MY_ROLE=1
    else
        HAS_MY_ROLE=0
    fi

    # Resource class
    RESOURCE_CLASS="${CLASS_WITH_MST}Resource"

    # Interface class
    INTERFACE_CLASS="${CLASS_WITH_MST}Interface"

    # Generate the PHP file (overwrite if exists)
    FILE="$DIR/$CLASS_NAME.php"
    
    # Generate the file using heredoc instead of multiple echo statements
    TABLE_SPACES=${TABLE//_/ }
    
    if [[ $IS_JUNCTION == 0 ]]; then
        # Regular table service
        HAS_HISTORY=0
        EXTRA_USES=""
        CONSTRUCTOR_PARAMS="protected $INTERFACE_CLASS \$${VAR_NAME}"
        if [[ $TABLE != *_hist ]]; then
            HAS_HISTORY=1
            HIST_TABLE=$(echo "$TABLE" | sed 's/$/_hist/')
            HIST_INTERFACE_CLASS=$(echo "$HIST_TABLE" | awk -F_ '{for(i=1;i<=NF;i++) printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } END{print "Interface"}')
            VAR_NAME_HIST=$(echo "$HIST_TABLE" | awk -F_ '{for(i=1;i<=NF;i++) { if(i==1) printf "%s", tolower(substr($i,1,1)) tolower(substr($i,2)); else printf "%s", toupper(substr($i,1,1)) tolower(substr($i,2)); } }')
            SUBPATH_HIST="History\\\\$SUBPATH"
            HIST_ID_FIELD="${TABLE}_id"
            EXTRA_USES="use App\\\\Interfaces\\\\$SUBPATH_HIST\\\\$HIST_INTERFACE_CLASS;
use App\\\\Enums\\\\ActionType;"
            CONSTRUCTOR_PARAMS="$CONSTRUCTOR_PARAMS, protected $HIST_INTERFACE_CLASS \$${VAR_NAME_HIST}"
        fi
        cat <<EOF > "$FILE"
<?php

namespace App\\Services\\$SUBPATH;

use App\\Interfaces\\$SUBPATH\\$INTERFACE_CLASS;
$EXTRA_USES
use Illuminate\\Http\\Resources\\Json\\JsonResource;
use App\\Http\\Resources\\$SUBPATH\\$RESOURCE_CLASS;

class $CLASS_NAME
{
    public function __construct(
        $CONSTRUCTOR_PARAMS
    )
    {
    }

    /**
     * Get $TABLE_SPACES list
     *
     * @param array \$payload
     * @return JsonResource
     */
    public function list(array \$payload): JsonResource
    {
        \$list = \$this->$VAR_NAME->list(\$payload);

        return ${RESOURCE_CLASS}::collection(\$list);
    }
EOF
        # Store function
        if [[ $HAS_HISTORY == 1 ]]; then
            cat <<EOF >> "$FILE"
    /**
     * Store $TABLE_SPACES
     *
     * @param array \$payload
     * @return int
     */
    public function store(array \$payload): int
    {
        \$id = \$this->$VAR_NAME->executeStore(\$payload);

        \$recordResource = \$this->list(['id' => \$id]);
        \$historyData = \$recordResource->collection->first()->toArray();
        \$historyPayload = \$historyData;
        unset(\$historyPayload['id']);
        \$historyPayload['$HIST_ID_FIELD'] = \$id;
        \$historyPayload['action'] = ActionType::CREATE;
        \$historyPayload['author_id'] = \$payload['author_id'] ?? null;
        \$historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        \$this->$VAR_NAME_HIST->executeStore(\$historyPayload);

        return \$id;
    }
EOF
        else
            cat <<EOF >> "$FILE"
    /**
     * Store $TABLE_SPACES
     *
     * @param array \$payload
     * @return int
     */
    public function store(array \$payload): int
    {
        return \$this->$VAR_NAME->executeStore(\$payload);
    }
EOF
        fi
        # Update function
        if [[ $HAS_HISTORY == 1 ]]; then
            cat <<EOF >> "$FILE"
    /**
     * Update $TABLE_SPACES
     *
     * @param array \$payload
     * @return int
     */
    public function update(array \$payload): int
    {
        \$id = \$payload['id'];
        \$affected = \$this->$VAR_NAME->executeUpdate(\$payload);

        \$recordResource = \$this->list(['id' => \$id]);
        \$historyData = \$recordResource->collection->first()->toArray();
        \$historyPayload = \$historyData;
        unset(\$historyPayload['id']);
        \$historyPayload['$HIST_ID_FIELD'] = \$id;
        \$historyPayload['action'] = ActionType::UPDATE;
        \$historyPayload['author_id'] = \$payload['author_id'] ?? null;
        \$historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        \$this->$VAR_NAME_HIST->executeStore(\$historyPayload);

        return \$affected;
    }
EOF
        else
            cat <<EOF >> "$FILE"
    /**
     * Update $TABLE_SPACES
     *
     * @param array \$payload
     * @return int
     */
    public function update(array \$payload): int
    {
        return \$this->$VAR_NAME->executeUpdate(\$payload);
    }
EOF
        fi
        # Delete function
        if [[ $HAS_HISTORY == 1 ]]; then
            cat <<EOF >> "$FILE"
    /**
     * Delete $TABLE_SPACES
     *
     * @param array \$payload
     * @return void
     */
    public function delete(array \$payload): void
    {
        if (!isset(\$payload['ids']) || !is_array(\$payload['ids'])) {
            \$this->$VAR_NAME->executeDelete(\$payload['ids'] ?? []);
            return;
        }

        foreach (\$payload['ids'] as \$id) {
            \$recordResource = \$this->list(['id' => \$id]);
            \$record = \$recordResource->collection->first();
            if (\$record) {
                \$historyData = \$record->toArray();
                \$historyPayload = \$historyData;
                unset(\$historyPayload['id']);
                \$historyPayload['$HIST_ID_FIELD'] = \$id;
                \$historyPayload['action'] = ActionType::DELETE;
                \$historyPayload['author_id'] = \$payload['author_id'] ?? null;
                \$historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                \$this->$VAR_NAME_HIST->executeStore(\$historyPayload);
            }
        }

        \$this->$VAR_NAME->executeDelete(\$payload['ids']);
    }
EOF
        else
            cat <<EOF >> "$FILE"
    /**
     * Delete $TABLE_SPACES
     *
     * @param array \$payload
     * @return void
     */
    public function delete(array \$payload): void
    {
        \$this->$VAR_NAME->executeDelete(\$payload['ids']);
    }
EOF
        fi
        cat <<EOF >> "$FILE"
}
EOF
    else
        # Junction table service
        cat <<EOF > "$FILE"
<?php

namespace App\\Services\\$SUBPATH;

use App\\Interfaces\\$SUBPATH\\$INTERFACE_CLASS;
use Illuminate\\Http\\Resources\\Json\\JsonResource;
use LogicException;
use App\\Constants\\Messages;
use App\\Constants\\CommonVal;
use App\\Http\\Resources\\$SUBPATH\\$RESOURCE_CLASS;

class $CLASS_NAME
{
    public function __construct(
        protected $INTERFACE_CLASS \$$VAR_NAME
    )
    {
    }

    /**
     * Get $TABLE_SPACES list
     *
     * @param array \$payload
     * @return JsonResource
     */
    public function list(array \$payload): JsonResource
    {
        \$list = \$this->$VAR_NAME->list(\$payload);

        return ${RESOURCE_CLASS}::collection(\$list);
    }

    /**
     * Update $TABLE_SPACES
     *
     * @param array \$payload
     * @return bool
     */
    public function update(array \$payload): bool
EOF

        # Conditionally add isMyRole check if needed
        if [[ $HAS_MY_ROLE == 1 ]]; then
            cat <<EOF >> "$FILE"
        // Don't allow editing of personal role without role admin
        if (\$this->$VAR_NAME->isMyRole(\$payload)) {
            throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
        }

EOF
        fi

        cat <<EOF >> "$FILE"
        // Delete $TABLE_SPACES
        if (\$payload['delete']) {
            self::checkExists${CLASS_WITH_MST}(\$payload['delete']);
            \$this->$VAR_NAME->executeDelete(\$payload['delete']);
        }

        // Insert $TABLE_SPACES
        if (\$payload['insert']) {
            self::checkNotExists${CLASS_WITH_MST}(\$payload['insert']);
            \$this->$VAR_NAME->executeStore(\$payload['insert']);
        }

        return true;
    }

    /**
     * Check exist $TABLE_SPACES
     *
     * @param array \$payload
     * @return void
     */
    private function checkExists${CLASS_WITH_MST}(array \$payload): void
    {
        \$values = collect(\$payload)->map(function (\$item) {
            // Make sure the data is an integer and escaped
            return '$ITEM_CASTS';
        })->all();

        \$${VAR_ID} = \$this->$VAR_NAME->$GET_SANS_ID(\$values);

        // Compare \$values and \$${VAR_ID}, get the differences
        \$differences = array_udiff(\$values, \$${VAR_ID}, function (\$a, \$b) {
            return strcmp((string)\$a, (string)\$b);
        });

        // Join the differences into a string
        \$diffString = implode(', ', \$differences);

        // Throw exception if there are differences
        if (!empty(\$differences)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0017,
                    [
                        'attributes' => __('messages.$ATTR_NAME') . ': ' . \$diffString,
                        'tableName' => __('messages.$TABLE_TRANS')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist $TABLE_SPACES
     *
     * @param array \$payload
     * @return void
     */
    private function checkNotExists${CLASS_WITH_MST}(array \$payload): void
    {
        \$values = collect(\$payload)->map(function (\$item) {
            // Make sure the data is an integer and escaped
            return '$ITEM_CASTS';
        })->all();

        \$${VAR_ID} = \$this->$VAR_NAME->$GET_WITH_ID(\$values)->toArray();
        \$diffString = implode(', ', \$${VAR_ID});

        // Throw exception if there are exist
        if (!empty(\$${VAR_ID})) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.$ATTR_NAME') . ': ' . \$diffString,
                        'tableName' => __('messages.$TABLE_TRANS')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
EOF
    fi

    echo "Generated/Overwritten: $FILE"
done