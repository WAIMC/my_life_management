#!/bin/bash

# Đường dẫn đến schema
SCHEMA="$(dirname "$0")/database/schema/pgsql-schema.json"
REPO_DIR="$(dirname "$0")/app/Repositories/Master"
INTERFACE_DIR="$(dirname "$0")/app/Interfaces/Master"

# Mẫu cho repository loại thường (1-1, 1-N)
read -r -d '' REPO_MODEL <<'EOF'
<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\{{ClassName}}Interface;
use App\Repositories\BaseRepository;
use App\Models\Master\{{ClassName}};
use Illuminate\Support\Collection;

class {{ClassName}}Repository extends BaseRepository implements {{ClassName}}Interface
{
    public function __construct({{ClassName}} $model)
    {
        parent::__construct($model);
    }

    public function list(array $payload): Collection
    {
        $query = $this->model->query();
        // ...add filter by $payload if needed...
        return $query->get();
    }

    public function executeStore(array $payload): int
    {
        $data = $this->model->create($payload);
        return $data->id;
    }

    public function executeUpdate(array $payload): int
    {
        $data = $this->model->find($payload['id']);
        $data->fill($payload);
        $data->save();
        return $data->id;
    }

    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
EOF

# Mẫu cho interface loại thường
read -r -d '' INTERFACE_MODEL <<'EOF'
<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface {{ClassName}}Interface
{
    public function list(array $payload): Collection;
    public function executeStore(array $payload): int;
    public function executeUpdate(array $payload): int;
    public function executeDelete(array $ids): void;
}
EOF

# Mẫu cho repository loại trung gian
read -r -d '' REPO_PIVOT <<'EOF'
<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\{{ClassName}}Interface;
use App\Repositories\BaseRepository;
use App\Models\Master\{{ClassName}};
use Illuminate\Support\Collection;

class {{ClassName}}Repository extends BaseRepository implements {{ClassName}}Interface
{
    public function __construct({{ClassName}} $model)
    {
        parent::__construct($model);
    }

    public function list(array $payload): Collection
    {
        $query = $this->model->query();
        // ...add filter by $payload if needed...
        return $query->get();
    }

    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    public function executeDelete(array $payload): void
    {
        // Xử lý xóa theo nhiều khóa chính
        // $payload là mảng các cặp khóa
        foreach ($payload as $item) {
            $this->model->where($item)->delete();
        }
    }
}
EOF

# Mẫu cho interface loại trung gian
read -r -d '' INTERFACE_PIVOT <<'EOF'
<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface {{ClassName}}Interface
{
    public function list(array $payload): Collection;
    public function executeStore(array $payload): void;
    public function executeDelete(array $payload): void;
}
EOF

# Hàm chuyển snake_case sang PascalCase
to_pascal() {
    echo "$1" | sed -r 's/(^|_)([a-z])/\U\2/g'
}

# Lấy danh sách bảng từ schema
TABLES=$(jq -r '.properties | keys[]' "$SCHEMA")

for TABLE in $TABLES; do
    # Loại bỏ các bảng đã có repository hoặc interface
    # (bạn có thể bổ sung logic kiểm tra file tồn tại nếu muốn)
    CLASS_NAME=$(to_pascal "$TABLE")
    REPO_FILE="$REPO_DIR/${CLASS_NAME}Repository.php"
    INTERFACE_FILE="$INTERFACE_DIR/${CLASS_NAME}Interface.php"

    # Lấy số lượng khóa chính để phân loại
    PK_COUNT=$(jq ".properties.\"$TABLE\".required | length" "$SCHEMA")

    # Nếu là bảng trung gian (có 2 khóa chính trở lên)
    if [ "$PK_COUNT" -ge 2 ]; then
        REPO_CONTENT="${REPO_PIVOT//\{\{ClassName\}\}/$CLASS_NAME}"
        INTERFACE_CONTENT="${INTERFACE_PIVOT//\{\{ClassName\}\}/$CLASS_NAME}"
    else
        REPO_CONTENT="${REPO_MODEL//\{\{ClassName\}\}/$CLASS_NAME}"
        INTERFACE_CONTENT="${INTERFACE_MODEL//\{\{ClassName\}\}/$CLASS_NAME}"
    fi

    # Tạo file repository
    mkdir -p "$REPO_DIR"
    echo "$REPO_CONTENT" > "$REPO_FILE"
    echo "Created $REPO_FILE"

    # Tạo file interface
    mkdir -p "$INTERFACE_DIR"
    echo "$INTERFACE_CONTENT" > "$INTERFACE_FILE"
    echo "Created $INTERFACE_FILE"
done

echo "Done!"
