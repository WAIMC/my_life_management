# OpenAPI / Swagger workflow (local sync)

This folder contains the standalone OpenAPI artifacts and helper scripts used to keep the API specification separated from the Laravel app code and synchronized into the Next.js frontend.

Overview
- `openapi.base.yaml` — base OpenAPI document shared across environments.
- `openapi.yaml` — generated, full OpenAPI spec (created by the generator).
- `index.html` — static Swagger UI that reads `openapi.yaml` and shows examples client-side.
- `generate_openapi.php` / `generate_openapi.sh` — small wrappers to run the generator and copy the resulting `openapi.yaml` into the Next.js app.

Goals
- Keep the OpenAPI spec isolated from app code.
- Allow a simple local workflow to update the spec and push it into `nextjs-dashboard`.
- Optionally generate TypeScript types from the spec into the Next.js codebase.
# OpenAPI / Swagger — quick guide

Short, focused instructions to regenerate the OpenAPI spec and sync it into the Next.js dashboard.

Quick (one-command)
```bash
cd laravel-api/openapi
./generate_openapi.sh
```

What it does
- Runs the inlined generator (`generate_openapi.php`) — prefers local `php`, falls back to the `ml-php` service in `docker/docker-compose.yml`.
- Writes `openapi/openapi.yaml` (base + generated paths).
- Copies the spec to `nextjs-dashboard/public/api/openapi.yaml`.
- Runs `pnpm run gen:api:types` in `nextjs-dashboard` if `pnpm` is available, producing `src/lib/api-types.ts`.

Manual steps

1) Regenerate spec only:
```bash
cd laravel-api/openapi
php generate_openapi.php
```

2) Copy spec to FE and generate types (if you prefer separate steps):
```bash
cp openapi.yaml ../../nextjs-dashboard/public/api/openapi.yaml
cd ../../nextjs-dashboard
pnpm install    # once
pnpm run gen:api:types
```

Troubleshooting
- If generator can't run because PHP/artisan is missing: run in container or install PHP CLI on host.
  Example container command:
  ```bash
  docker compose -f docker/docker-compose.yml run --rm ml-php sh -lc "cd /var/www/laravel-api/openapi && php generate_openapi.php"
  ```
- If `openapi.yaml` is created by a container and owned by `root`, the wrapper attempts a best-effort `chown`. To avoid this permanently, set `user: "${UID}:${GID}"` for `ml-php` in your compose file.
- If `pnpm` / `openapi-typescript` missing: run `pnpm install` in `nextjs-dashboard` or install `openapi-typescript` as a dev dependency.

Notes
- `openapi.base.yaml` is the hand-maintained base (components, security). The generator only adds/updates `paths`.
- The included generator is minimal — extend it if you want richer schemas/examples.

Want automation?
- I can add a GitHub Action that runs the generator and opens a PR with updated spec/types when routes change.

./generate_openapi.sh
```

This runs `php artisan route:list --json` (the script changes directory to the laravel-api root) and creates/updates `openapi/openapi.yaml`.

Preview locally:

```bash
# in laravel-api/openapi
./serve_swagger.sh 8080
# then open http://localhost:8080
```

Automation suggestions:
- Add a git pre-commit or pre-push hook to run the generator when routes change.
- Add a CI job that runs the generator and commits or uploads the new `openapi.yaml` artifact.
- Optionally, enhance `scripts/generate_openapi.php` to include docblock parsing from controllers or to read route action comments.

Notes:
- The generator uses simple heuristics to include routes with `api` prefix or `api` middleware. You can adjust `scripts/generate_openapi.php` to suit your conventions.
- This approach intentionally avoids changing any Laravel application files.
