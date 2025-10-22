#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

PORT=${1:-8080}
echo "Serving Swagger UI at http://localhost:$PORT (openapi folder)"
# Use python's http.server for quick static serving
python3 -m http.server "$PORT"
