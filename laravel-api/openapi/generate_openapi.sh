#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

echo "=== OpenAPI one-command sync ==="

# current user ids (used for container --user and chown attempts)
HOST_UID=$(id -u)
HOST_GID=$(id -g)

run_local() {
  if command -v php >/dev/null 2>&1 && [ -f generate_openapi.php ]; then
    echo "Found local php and generator; running: php generate_openapi.php"
    php generate_openapi.php || true
    return 0
  fi
  return 1
}

run_container() {
  # locate docker-compose file
  COMPOSE_FILE=""
  if [ -f ../../docker/docker-compose.yml ]; then
    COMPOSE_FILE="$(realpath ../../docker/docker-compose.yml)"
  elif [ -f ../../docker-compose.yml ]; then
    COMPOSE_FILE="$(realpath ../../docker-compose.yml)"
  fi

  if [ -z "$COMPOSE_FILE" ]; then
    echo "No docker-compose found at ../../docker/docker-compose.yml or ../../docker-compose.yml"
    return 1
  fi

  echo "Attempting containerized run using compose file: $COMPOSE_FILE"
  if command -v docker >/dev/null 2>&1 && docker compose version >/dev/null 2>&1; then
    docker compose -f "$COMPOSE_FILE" run --rm --user "$HOST_UID:$HOST_GID" ml-php sh -lc "cd /var/www/laravel-api/openapi && php generate_openapi.php" || true
    return 0
  elif command -v docker-compose >/dev/null 2>&1; then
    docker-compose -f "$COMPOSE_FILE" run --rm --user "$HOST_UID:$HOST_GID" ml-php sh -lc "cd /var/www/laravel-api/openapi && php generate_openapi.php" || true
    return 0
  else
    echo "Docker present but compose plugin not available; cannot run containerized generator."
    return 1
  fi
}

echo "Running generator (local -> container fallback)..."
if run_local; then
  echo "Local generator executed (or attempted)."
else
  echo "Local php/generator not available; trying container fallback..."
  if ! run_container; then
    echo "Containerized generator failed or not available."
    echo "Ensure 'laravel-api/scripts/generate_openapi.php' exists or run the generator manually."
  fi
fi

# Ensure the generated spec exists
if [ ! -f openapi.yaml ]; then
  echo "No openapi.yaml generated; nothing to sync."
  echo "If you expect generation to work in container, ensure 'laravel-api/scripts/generate_openapi.php' exists and is mounted into /var/www/laravel-api/scripts/."
  exit 0
fi

# Fix ownership (best-effort)
if [ "$(stat -c %u openapi.yaml 2>/dev/null || echo unknown)" = "0" ]; then
  echo "openapi.yaml appears root-owned; attempting chown to $HOST_UID:$HOST_GID"
  if chown $HOST_UID:$HOST_GID openapi.yaml 2>/dev/null; then
    echo "Chown succeeded"
  else
    echo "Direct chown failed; attempting to fix ownership using a temporary container (will pull image if needed)"
    if command -v docker >/dev/null 2>&1; then
      # run an ephemeral container mounting current dir and chowning the file
      docker run --rm -v "$(pwd)":/work alpine:latest sh -c "chown $HOST_UID:$HOST_GID /work/openapi.yaml || true"
      echo "Attempted chown via container helper"
    else
      echo "Docker not available locally; cannot attempt container chown. The copied file may be root-owned."
    fi
  fi
fi

# Copy into nextjs public folder
NEXTJS_DIR="$(realpath ../..)/nextjs-dashboard"
NEXTJS_SPEC_DIR="$NEXTJS_DIR/public/api"
if [ -d "$NEXTJS_DIR" ]; then
  mkdir -p "$NEXTJS_SPEC_DIR"
  cp -f openapi.yaml "$NEXTJS_SPEC_DIR/openapi.yaml"
  echo "Copied openapi.yaml -> $NEXTJS_SPEC_DIR/openapi.yaml"

  # Run TypeScript generation if package.json present
  if [ -f "$NEXTJS_DIR/package.json" ]; then
    echo "Running type generation in nextjs-dashboard..."
    if command -v pnpm >/dev/null 2>&1; then
      (cd "$NEXTJS_DIR" && pnpm run gen:api:types) && echo "Generated TypeScript types (pnpm)" || echo "Type generation failed; run 'pnpm run gen:api:types' in $NEXTJS_DIR"
    elif command -v npm >/dev/null 2>&1; then
      (cd "$NEXTJS_DIR" && npm run gen:api:types) && echo "Type generation failed; run 'npm run gen:api:types' in $NEXTJS_DIR"
    else
      echo "No pnpm/npm found locally; cannot run type generation. Run it manually in $NEXTJS_DIR"
    fi
  fi
else
  echo "Next.js dashboard not found at $NEXTJS_DIR — skipping copy and type generation"
fi

echo "=== Done ==="
