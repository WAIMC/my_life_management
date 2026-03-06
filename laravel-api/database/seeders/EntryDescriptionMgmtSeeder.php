<?php

namespace Database\Seeders;

use App\Models\Management\EntryDescriptionMgmt;
use App\Models\Management\EntryMgmt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntryDescriptionMgmtSeeder extends Seeder
{
  public function run(): void
  {
    DB::transaction(function () {
      $descriptions = [
        // ─── Getting Started ────────────────────────────
        [
          'entry_slug' => 'installation',
          'descriptions' => [
            [
              'title' => 'Prerequisites',
              'summary' => 'System requirements and prerequisites for installation',
              'article' => '<h2>System Requirements</h2><p>Before installing, ensure your system meets the following requirements:</p><ul><li>Docker 20.10+</li><li>Docker Compose 2.0+</li><li>4 GB RAM minimum</li><li>10 GB free disk space</li></ul><h3>Required Knowledge</h3><ul><li>Command line interface</li><li>Docker containers</li><li>Environment variables</li></ul>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Clone Repository',
              'summary' => 'How to clone and set up the project repository',
              'article' => '<h2>Cloning the Repository</h2><p>Clone the repository from GitHub:</p><pre><code>git clone https://github.com/your-org/second-memory.git\ncd second-memory</code></pre><h3>Directory Structure</h3><ul><li><code>docker/</code> - Docker configuration files</li><li><code>laravel-api/</code> - Backend Laravel application</li><li><code>nextjs-docs/</code> - Documentation app</li><li><code>nextjs-fe/</code> - Frontend Next.js application</li></ul>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Environment Setup',
              'summary' => 'Configure environment variables and Docker',
              'article' => '<h2>Setting Up Environment Variables</h2><p>Copy the example environment file and configure it:</p><pre><code>cd docker\ncp .env.example .env</code></pre><h3>Key Variables</h3><ul><li><code>POSTGRES_DB</code> - Database name</li><li><code>POSTGRES_USER</code> - Database user</li><li><code>POSTGRES_PASSWORD</code> - Database password</li><li><code>REDIS_PASSWORD</code> - Redis password</li></ul>',
              'rank_order' => 3,
            ],
            [
              'title' => 'Start Services',
              'summary' => 'Launch Docker containers and verify installation',
              'article' => '<h2>Starting Docker Services</h2><p>Use Docker Compose to start all services:</p><pre><code>docker-compose up -d</code></pre><h3>Verify Services</h3><pre><code>docker-compose ps</code></pre><p>You should see:</p><ul><li>ml-postgres (PostgreSQL database)</li><li>ml-redis (Redis cache)</li><li>ml-php (Laravel PHP-FPM)</li><li>ml-nextjs (Next.js frontend)</li><li>ml-nginx (Nginx web server)</li></ul>',
              'rank_order' => 4,
            ],
            [
              'title' => 'Running Migrations & Seeders',
              'summary' => 'Initialize the database with schema and seed data',
              'article' => '<h2>Database Initialization</h2><p>After starting the services, run migrations and seeders:</p><pre><code>docker exec ml-php php artisan migrate\ndocker exec ml-php php artisan db:seed</code></pre><h3>Verify Data</h3><p>Check that the seed data loaded correctly:</p><pre><code>docker exec ml-postgres psql -U ml_pg_user -d ml_pg_db -c "SELECT COUNT(*) FROM category_mgmts;"</code></pre>',
              'rank_order' => 5,
            ],
          ],
        ],

        [
          'entry_slug' => 'project-structure',
          'descriptions' => [
            [
              'title' => 'Overview',
              'summary' => 'Understanding the overall project structure',
              'article' => '<h2>Project Structure Overview</h2><p>The project follows a modular monorepo with clear separation of concerns:</p><pre><code>second-memory/\n├── docker/          # Docker & Nginx config\n├── laravel-api/     # Backend REST API\n├── nextjs-docs/     # Documentation site\n└── nextjs-fe/       # Main frontend app</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Backend Structure',
              'summary' => 'Laravel API directory organization',
              'article' => '<h2>Laravel API Structure</h2><pre><code>laravel-api/\n├── app/\n│   ├── Http/\n│   │   ├── Controllers/\n│   │   ├── Requests/\n│   │   └── Resources/\n│   ├── Models/\n│   ├── Services/\n│   └── Repositories/\n├── database/\n│   ├── migrations/\n│   └── seeders/\n└── routes/\n    └── api.php</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Frontend Structure',
              'summary' => 'Next.js application directory organization',
              'article' => '<h2>Next.js Frontend Structure</h2><pre><code>nextjs-fe/\n├── src/\n│   ├── app/\n│   ├── components/\n│   ├── lib/\n│   ├── services/\n│   └── types/\n├── public/\n└── messages/</code></pre>',
              'rank_order' => 3,
            ],
            [
              'title' => 'Docker Configuration',
              'summary' => 'How Docker files are organized',
              'article' => '<h2>Docker Structure</h2><pre><code>docker/\n├── nginx/\n│   ├── nginx.conf\n│   └── conf.d/\n├── docker-compose.yml\n├── .env.example\n└── .env</code></pre><p>Each service has its own Dockerfile co-located with its source code.</p>',
              'rank_order' => 4,
            ],
          ],
        ],

        [
          'entry_slug' => 'configuration',
          'descriptions' => [
            [
              'title' => 'Database Configuration',
              'summary' => 'Setting up PostgreSQL database connection',
              'article' => '<h2>Database Configuration</h2><p>Configure your database in Laravel\'s <code>.env</code>:</p><pre><code>DB_CONNECTION=pgsql\nDB_HOST=ml-postgres\nDB_PORT=5432\nDB_DATABASE=ml_pg_db\nDB_USERNAME=ml_pg_user\nDB_PASSWORD=ml_pg_password</code></pre><h3>Running Migrations</h3><pre><code>docker exec ml-php php artisan migrate</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Cache Configuration',
              'summary' => 'Setting up Redis cache',
              'article' => '<h2>Redis Cache Configuration</h2><pre><code>REDIS_HOST=ml-redis\nREDIS_PORT=6379\nREDIS_PASSWORD=ml_redis_password\nCACHE_DRIVER=redis\nSESSION_DRIVER=redis</code></pre><p>Test the Redis connection:</p><pre><code>docker exec ml-redis redis-cli ping</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Application Keys',
              'summary' => 'Generating and managing application keys',
              'article' => '<h2>App Key Generation</h2><p>Generate the Laravel application key:</p><pre><code>docker exec ml-php php artisan key:generate</code></pre><p>Store secrets securely and never commit your <code>.env</code> to version control.</p>',
              'rank_order' => 3,
            ],
            [
              'title' => 'Queue Configuration',
              'summary' => 'Setting up background jobs and queues',
              'article' => '<h2>Queue Setup</h2><pre><code>QUEUE_CONNECTION=redis</code></pre><p>Start the queue worker:</p><pre><code>docker exec ml-php php artisan queue:work</code></pre><h3>Horizon</h3><p>For monitoring, install Laravel Horizon and open <code>/horizon</code>.</p>',
              'rank_order' => 4,
            ],
          ],
        ],

        // ─── Architecture ──────────────────────────────
        [
          'entry_slug' => 'overview',
          'descriptions' => [
            [
              'title' => 'System Architecture',
              'summary' => 'High-level system architecture overview',
              'article' => '<h2>System Architecture</h2><p>The application uses a layered architecture with distinct frontend, backend, and infrastructure layers:</p><ul><li><strong>Frontend:</strong> Next.js 16 (App Router)</li><li><strong>Backend:</strong> Laravel 11 REST API</li><li><strong>Database:</strong> PostgreSQL 16</li><li><strong>Cache:</strong> Redis 7</li><li><strong>Proxy:</strong> Nginx</li></ul>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Technology Stack',
              'summary' => 'Technologies and frameworks used',
              'article' => '<h2>Technology Stack</h2><h3>Frontend</h3><ul><li>Next.js 16</li><li>React 19</li><li>TypeScript</li><li>Tailwind CSS</li></ul><h3>Backend</h3><ul><li>Laravel 11</li><li>PHP 8.3</li><li>PostgreSQL 16</li><li>Redis 7</li></ul><h3>DevOps</h3><ul><li>Docker & Docker Compose</li><li>Nginx</li></ul>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Data Flow',
              'summary' => 'How data moves through the system',
              'article' => '<h2>Request Data Flow</h2><p>A typical request flows as follows:</p><pre><code>Browser\n  → Nginx (Reverse Proxy)\n  → Next.js (SSR / CSR)\n  → Laravel API (REST)\n  → PostgreSQL / Redis</code></pre><p>Responses go back through the same chain in reverse.</p>',
              'rank_order' => 3,
            ],
          ],
        ],

        [
          'entry_slug' => 'layered-architecture',
          'descriptions' => [
            [
              'title' => 'Layers Overview',
              'summary' => 'Understanding the layered architecture pattern',
              'article' => '<h2>Layered Architecture</h2><ol><li><strong>Presentation Layer:</strong> Controllers, API Resources</li><li><strong>Business Logic Layer:</strong> Services</li><li><strong>Data Access Layer:</strong> Repositories</li><li><strong>Domain Layer:</strong> Models, Entities</li></ol><h3>Benefits</h3><ul><li>Separation of concerns</li><li>Testability</li><li>Maintainability</li></ul>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Request Flow',
              'summary' => 'How requests flow through the layers',
              'article' => '<h2>Request Flow</h2><pre><code>Route → Middleware → Controller\n  ↓\nForm Request (Validation)\n  ↓\nService (Business Logic)\n  ↓\nRepository (Data Access)\n  ↓\nModel → Database\n  ↓\nAPI Resource (Transform)\n  ↓\nJSON Response</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'design-patterns',
          'descriptions' => [
            [
              'title' => 'Repository Pattern',
              'summary' => 'Using the Repository pattern for data access',
              'article' => '<h2>Repository Pattern</h2><p>Repositories abstract the data layer from business logic:</p><pre><code>interface EntryRepositoryInterface {\n    public function findBySlug(string $slug): ?Entry;\n    public function create(array $data): Entry;\n}\n\nclass EntryRepository implements EntryRepositoryInterface {\n    public function findBySlug(string $slug): ?Entry {\n        return Entry::where(\'slug\', $slug)->first();\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Service Layer Pattern',
              'summary' => 'Encapsulating business logic in services',
              'article' => '<h2>Service Layer</h2><p>Services contain business logic and depend on repositories:</p><pre><code>class EntryService {\n    public function __construct(\n        private EntryRepositoryInterface $entryRepo\n    ) {}\n\n    public function findEntry(string $slug): EntryResource {\n        $entry = $this->entryRepo->findBySlug($slug);\n        if (!$entry) throw new NotFoundException();\n        return new EntryResource($entry);\n    }\n}</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Observer Pattern',
              'summary' => 'Reacting to model events with Observers',
              'article' => '<h2>Model Observers</h2><p>Laravel observers allow you to react to Eloquent model events:</p><pre><code>class EntryObserver {\n    public function creating(Entry $entry): void {\n        $entry->slug = Str::slug($entry->name);\n    }\n\n    public function deleting(Entry $entry): void {\n        $entry->descriptions()->delete();\n    }\n}</code></pre><p>Register the observer in a ServiceProvider.</p>',
              'rank_order' => 3,
            ],
          ],
        ],

        // ─── API Reference ─────────────────────────────
        [
          'entry_slug' => 'authentication',
          'descriptions' => [
            [
              'title' => 'JWT Overview',
              'summary' => 'How JWT authentication works in this project',
              'article' => '<h2>JWT Authentication</h2><p>The API uses JWT (JSON Web Tokens) for stateless authentication. Tokens are issued on login and refreshed automatically.</p><h3>Flow</h3><ol><li>POST <code>/api/auth/login</code> with credentials</li><li>Receive <code>access_token</code> and <code>refresh_token</code></li><li>Include in subsequent requests as <code>Authorization: Bearer {token}</code></li></ol>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Login Endpoint',
              'summary' => 'POST /api/auth/login endpoint details',
              'article' => '<h2>Login</h2><pre><code>POST /api/auth/login\nContent-Type: application/json\n\n{\n  "email": "admin@example.com",\n  "password": "secret"\n}</code></pre><h3>Response</h3><pre><code>{\n  "data": {\n    "access_token": "eyJ...",\n    "token_type": "bearer",\n    "expires_in": 3600\n  }\n}</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Token Refresh',
              'summary' => 'How to refresh expired tokens',
              'article' => '<h2>Refresh Token</h2><pre><code>POST /api/auth/refresh\nAuthorization: Bearer {expired_token}</code></pre><p>Returns a new access token. Old token is invalidated.</p>',
              'rank_order' => 3,
            ],
          ],
        ],

        [
          'entry_slug' => 'categories-api',
          'descriptions' => [
            [
              'title' => 'List Categories',
              'summary' => 'GET /api/docs/categories endpoint',
              'article' => '<h2>List Categories</h2><pre><code>GET /api/docs/categories</code></pre><h3>Response</h3><pre><code>{\n  "data": [\n    {\n      "id": 1,\n      "name": "Getting Started",\n      "slug": "getting-started",\n      "description": "...",\n      "rank_order": 1\n    }\n  ]\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Category Entries',
              'summary' => 'GET /api/docs/categories/{slug}/entries endpoint',
              'article' => '<h2>Category Entries</h2><pre><code>GET /api/docs/categories/{slug}/entries</code></pre><p>Returns all entries belonging to the given category slug.</p>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'entries-api',
          'descriptions' => [
            [
              'title' => 'Get Entry Detail',
              'summary' => 'GET /api/docs/entries/{slug} endpoint',
              'article' => '<h2>Entry Detail</h2><pre><code>GET /api/docs/entries/{slug}</code></pre><h3>Response</h3><pre><code>{\n  "data": {\n    "id": 1,\n    "name": "Installation",\n    "slug": "installation",\n    "descriptions": [...]\n  }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Create Entry',
              'summary' => 'POST /api/docs/entries endpoint',
              'article' => '<h2>Create Entry</h2><pre><code>POST /api/docs/entries\nAuthorization: Bearer {token}\nContent-Type: application/json\n\n{\n  "name": "My New Entry",\n  "category_slug": "getting-started",\n  "rank_order": 5\n}</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Update Entry',
              'summary' => 'PUT /api/docs/entries/{slug} endpoint',
              'article' => '<h2>Update Entry</h2><pre><code>PUT /api/docs/entries/{slug}\nAuthorization: Bearer {token}\n\n{\n  "name": "Updated Name",\n  "rank_order": 2\n}</code></pre>',
              'rank_order' => 3,
            ],
          ],
        ],

        [
          'entry_slug' => 'search-api',
          'descriptions' => [
            [
              'title' => 'Search Endpoint',
              'summary' => 'GET /api/docs/search endpoint',
              'article' => '<h2>Search API</h2><pre><code>GET /api/docs/search?q=installation</code></pre><h3>Response</h3><pre><code>{\n  "data": {\n    "categories": [...],\n    "entries": [...],\n    "descriptions": [...]\n  }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Search Filters',
              'summary' => 'Advanced search options and filters',
              'article' => '<h2>Search Filters</h2><p>You can filter results by type:</p><pre><code>GET /api/docs/search?q=docker&type=entries</code></pre><p>Available types: <code>categories</code>, <code>entries</code>, <code>descriptions</code>, <code>all</code> (default).</p>',
              'rank_order' => 2,
            ],
          ],
        ],

        // ─── Frontend ──────────────────────────────────
        [
          'entry_slug' => 'nextjs-setup',
          'descriptions' => [
            [
              'title' => 'Next.js Configuration',
              'summary' => 'Setting up Next.js with TypeScript',
              'article' => '<h2>Next.js Setup</h2><p>The frontend uses Next.js 16 with App Router and TypeScript strict mode.</p><pre><code>// next.config.ts\nconst nextConfig = {\n  images: { unoptimized: true },\n};\nexport default nextConfig;</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Environment Variables',
              'summary' => 'Configuring Next.js environment variables',
              'article' => '<h2>Environment Variables</h2><pre><code>NEXT_PUBLIC_API_URL=http://localhost:80/api\nNEXT_PUBLIC_DOCS_API_URL=http://localhost:80/api/docs</code></pre><p>Variables prefixed with <code>NEXT_PUBLIC_</code> are available in the browser.</p>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'routing',
          'descriptions' => [
            [
              'title' => 'App Router Structure',
              'summary' => 'Understanding Next.js App Router',
              'article' => '<h2>App Router</h2><p>Next.js 13+ uses the App Router. Pages are created by adding a <code>page.tsx</code> file in any directory under <code>src/app/</code>.</p><pre><code>src/app/\n├── page.tsx          # /\n├── docs/\n│   ├── page.tsx      # /docs\n│   └── [slug]/\n│       └── page.tsx  # /docs/:slug</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Dynamic Routes',
              'summary' => 'How dynamic segments work',
              'article' => '<h2>Dynamic Routes</h2><p>Dynamic segments are wrapped in square brackets: <code>[slug]</code>.</p><pre><code>// src/app/docs/[categorySlug]/[entrySlug]/page.tsx\nexport default async function Page({ params }) {\n  const { categorySlug, entrySlug } = await params;\n  ...\n}</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Layouts',
              'summary' => 'Nested layouts in Next.js',
              'article' => '<h2>Layouts</h2><p>Layouts wrap multiple pages and persist across navigations:</p><pre><code>// src/app/docs/[categorySlug]/layout.tsx\nexport default function Layout({ children }) {\n  return (\n    <div className="flex">\n      <Sidebar />\n      <main>{children}</main>\n    </div>\n  );\n}</code></pre>',
              'rank_order' => 3,
            ],
          ],
        ],

        [
          'entry_slug' => 'state-management',
          'descriptions' => [
            [
              'title' => 'Server vs Client State',
              'summary' => 'Understanding state strategies in Next.js',
              'article' => '<h2>State Strategies</h2><p>In Next.js App Router:</p><ul><li><strong>Server State:</strong> Fetched in Server Components, no client JavaScript needed</li><li><strong>Client State:</strong> Managed with React hooks or TanStack Query</li><li><strong>URL State:</strong> Stored in search params for sharable UI state</li></ul>',
              'rank_order' => 1,
            ],
            [
              'title' => 'TanStack Query',
              'summary' => 'Using TanStack Query for data fetching',
              'article' => '<h2>TanStack Query</h2><pre><code>import { useQuery } from "@tanstack/react-query";\n\nfunction MyComponent() {\n  const { data } = useQuery({\n    queryKey: ["entries"],\n    queryFn: () => fetch("/api/entries").then(r => r.json()),\n  });\n  return <pre>{JSON.stringify(data, null, 2)}</pre>;\n}</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'styling-tailwind',
          'descriptions' => [
            [
              'title' => 'Tailwind CSS v4 Setup',
              'summary' => 'How Tailwind CSS v4 is configured',
              'article' => '<h2>Tailwind CSS v4</h2><p>Tailwind v4 uses a CSS-first configuration approach. All setup is in <code>globals.css</code>:</p><pre><code>@import "tailwindcss";\n\n@theme inline {\n  --color-primary: oklch(0.6 0.2 270);\n  --font-sans: "Geist", sans-serif;\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Component Styling',
              'summary' => 'Approach to styling components',
              'article' => '<h2>Component Styling</h2><p>We use utility-first classes with the <code>cn()</code> helper for conditional styles:</p><pre><code>import { cn } from "@/lib/utils";\n\nfunction Button({ isActive }) {\n  return (\n    <button\n      className={cn(\n        "px-4 py-2 rounded-md font-medium",\n        isActive ? "bg-blue-600 text-white" : "bg-gray-100 text-gray-600"\n      )}\n    />\n  );\n}</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        // ─── Backend ───────────────────────────────────
        [
          'entry_slug' => 'laravel-setup',
          'descriptions' => [
            [
              'title' => 'PHP Configuration',
              'summary' => 'PHP and Laravel server configuration',
              'article' => '<h2>PHP Configuration</h2><p>The Laravel API runs in a Docker container with PHP 8.3 and PHP-FPM.</p><pre><code># Dockerfile (laravel-api)\nFROM php:8.3-fpm-alpine\n\nRUN docker-php-ext-install pdo pgsql pdo_pgsql\n\nCOPY . /var/www/html\nRUN composer install --optimize-autoloader --no-dev</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Composer Dependencies',
              'summary' => 'Managing PHP packages with Composer',
              'article' => '<h2>Composer Setup</h2><p>Install dependencies inside the container:</p><pre><code>docker exec ml-php composer install</code></pre><h3>Key Packages</h3><ul><li><code>tymon/jwt-auth</code> - JWT authentication</li><li><code>spatie/laravel-permission</code> - Role & Permission</li><li><code>laravel/sanctum</code> - API auth</li></ul>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'controllers-services',
          'descriptions' => [
            [
              'title' => 'Controller Structure',
              'summary' => 'Organizing controllers in the project',
              'article' => '<h2>Controllers</h2><p>Controllers are kept thin, delegating to services:</p><pre><code>class EntryController extends Controller {\n    public function __construct(\n        private EntryService $service\n    ) {}\n\n    public function show(string $slug): JsonResponse {\n        return $this->service->findEntry($slug);\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Services Layer',
              'summary' => 'Writing business logic in services',
              'article' => '<h2>Services</h2><p>Business logic lives in the service layer:</p><pre><code>class EntryService {\n    public function findEntry(string $slug): JsonResponse {\n        $entry = $this->entryRepo->findBySlug($slug);\n        abort_unless($entry, 404, "Entry not found");\n        return response()->json(new EntryResource($entry));\n    }\n}</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'repository-pattern',
          'descriptions' => [
            [
              'title' => 'Repository Interface',
              'summary' => 'Defining repository contracts',
              'article' => '<h2>Repository Interface</h2><pre><code>interface EntryRepositoryInterface {\n    public function all(): Collection;\n    public function findBySlug(string $slug): ?Entry;\n    public function create(array $data): Entry;\n    public function update(Entry $entry, array $data): Entry;\n    public function delete(Entry $entry): void;\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Concrete Repository',
              'summary' => 'Implementing the repository',
              'article' => '<h2>Eloquent Repository</h2><pre><code>class EntryRepository implements EntryRepositoryInterface {\n    public function findBySlug(string $slug): ?Entry {\n        return Entry::where(\'slug\', $slug)\n            ->with(\'descriptions\')\n            ->first();\n    }\n}</code></pre><p>Bind the interface in a ServiceProvider to swap implementations easily.</p>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'api-resources',
          'descriptions' => [
            [
              'title' => 'API Resource Classes',
              'summary' => 'Transforming model data for API responses',
              'article' => '<h2>API Resources</h2><p>Resources transform Eloquent models into JSON:</p><pre><code>class EntryResource extends JsonResource {\n    public function toArray(Request $request): array {\n        return [\n            "id" => $this->id,\n            "name" => $this->name,\n            "slug" => $this->slug,\n            "descriptions" => EntryDescriptionResource::collection(\n                $this->descriptions\n            ),\n        ];\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
          ],
        ],

        // ─── Database ──────────────────────────────────
        [
          'entry_slug' => 'migrations',
          'descriptions' => [
            [
              'title' => 'Creating Migrations',
              'summary' => 'How to create and run database migrations',
              'article' => '<h2>Database Migrations</h2><p>Generate a migration file:</p><pre><code>php artisan make:migration create_entries_table</code></pre><h3>Example Migration</h3><pre><code>Schema::create(\'entry_mgmts\', function (Blueprint $table) {\n    $table->id();\n    $table->string(\'name\');\n    $table->string(\'slug\')->unique();\n    $table->integer(\'rank_order\')->default(0);\n    $table->boolean(\'is_display\')->default(true);\n    $table->timestamps();\n});</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Rollback & Refresh',
              'summary' => 'Rolling back and refreshing migrations',
              'article' => '<h2>Migration Commands</h2><pre><code># Run all pending\nphp artisan migrate\n\n# Rollback last batch\nphp artisan migrate:rollback\n\n# Wipe and re-run all\nphp artisan migrate:fresh --seed</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'models-relations',
          'descriptions' => [
            [
              'title' => 'Eloquent Models',
              'summary' => 'Working with Eloquent models',
              'article' => '<h2>Eloquent Models</h2><p>Models represent database tables:</p><pre><code>class EntryMgmt extends Model {\n    protected $fillable = [\'name\', \'slug\', \'rank_order\'];\n\n    public function descriptions(): HasMany {\n        return $this->hasMany(EntryDescriptionMgmt::class);\n    }\n\n    public function categories(): BelongsToMany {\n        return $this->belongsToMany(\n            CategoryMgmt::class,\n            \'category_entry_mgmts\'\n        );\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Eager Loading',
              'summary' => 'Preventing N+1 queries with eager loading',
              'article' => '<h2>Eager Loading</h2><p>Always eager load relationships to avoid N+1:</p><pre><code>// Bad - N+1 queries\n$entries = Entry::all();\nforeach ($entries as $entry) {\n    echo $entry->descriptions->count();\n}\n\n// Good - 2 queries\n$entries = Entry::with(\'descriptions\')->get();</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'seeders',
          'descriptions' => [
            [
              'title' => 'Creating Seeders',
              'summary' => 'Writing database seeders',
              'article' => '<h2>Database Seeders</h2><pre><code>php artisan make:seeder EntryMgmtSeeder</code></pre><p>Then define data in the <code>run()</code> method using <code>firstOrCreate</code> to be idempotent:</p><pre><code>Entry::firstOrCreate(\n    [\'slug\' => $data[\'slug\']],\n    $data\n);</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Running Seeders',
              'summary' => 'How to seed the database',
              'article' => '<h2>Running Seeders</h2><pre><code># Run all seeders\nphp artisan db:seed\n\n# Run a specific seeder\nphp artisan db:seed --class=EntryMgmtSeeder\n\n# Inside Docker container\ndocker exec ml-php php artisan db:seed</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        // ─── Deployment ────────────────────────────────
        [
          'entry_slug' => 'docker-configuration',
          'descriptions' => [
            [
              'title' => 'Docker Compose',
              'summary' => 'Understanding the Docker Compose setup',
              'article' => '<h2>Docker Compose Configuration</h2><p>The application uses Docker Compose to orchestrate multiple services:</p><pre><code>services:\n  ml-postgres:\n    image: postgres:16-alpine\n  ml-redis:\n    image: redis:7-alpine\n  ml-php:\n    build: ./laravel-api\n  ml-nextjs:\n    build: ./nextjs-fe\n  ml-nginx:\n    image: nginx:alpine</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Service Dependencies',
              'summary' => 'How services depend on each other',
              'article' => '<h2>Service Dependencies</h2><p>Services start in the following order:</p><ol><li>PostgreSQL (database)</li><li>Redis (cache)</li><li>PHP-FPM (Laravel)</li><li>Next.js (frontend)</li><li>Nginx (reverse proxy)</li></ol><p>Health checks ensure services are ready before dependent services start.</p>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Volumes & Networking',
              'summary' => 'Docker volumes and internal networking',
              'article' => '<h2>Volumes & Networks</h2><pre><code>volumes:\n  postgres_data:\n  redis_data:\n\nnetworks:\n  ml_network:\n    driver: bridge</code></pre><p>All containers share the <code>ml_network</code>. Data is persisted through named volumes.</p>',
              'rank_order' => 3,
            ],
          ],
        ],

        [
          'entry_slug' => 'environment-variables',
          'descriptions' => [
            [
              'title' => 'Docker Environment',
              'summary' => 'Environment variables for Docker services',
              'article' => '<h2>Docker .env File</h2><p>Location: <code>docker/.env</code></p><pre><code>POSTGRES_DB=ml_pg_db\nPOSTGRES_USER=ml_pg_user\nPOSTGRES_PASSWORD=super_secret\n\nREDIS_PASSWORD=redis_secret\n\nAPP_URL=http://localhost:80\nNEXT_PUBLIC_API_URL=http://localhost:80/api</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Laravel .env',
              'summary' => 'Laravel application environment variables',
              'article' => '<h2>Laravel .env Variables</h2><pre><code>APP_NAME="Second Memory"\nAPP_ENV=production\nAPP_KEY=base64:...\nAPP_DEBUG=false\nAPP_URL=http://localhost\n\nDB_CONNECTION=pgsql\nDB_HOST=ml-postgres\nDB_PORT=5432\n\nCACHE_DRIVER=redis\nQUEUE_CONNECTION=redis</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'Secrets Management',
              'summary' => 'Best practices for managing secrets',
              'article' => '<h2>Managing Secrets</h2><ul><li>Never commit <code>.env</code> files to version control</li><li>Use <code>.env.example</code> as a template</li><li>In production, use Docker secrets or a vault</li><li>Rotate credentials regularly</li></ul>',
              'rank_order' => 3,
            ],
          ],
        ],

        [
          'entry_slug' => 'production-setup',
          'descriptions' => [
            [
              'title' => 'Production Checklist',
              'summary' => 'Steps to deploy to production',
              'article' => '<h2>Production Deployment Checklist</h2><ul><li>Set <code>APP_ENV=production</code> and <code>APP_DEBUG=false</code></li><li>Generate a fresh <code>APP_KEY</code></li><li>Run <code>php artisan config:cache</code> and <code>route:cache</code></li><li>Configure HTTPS with SSL certificates</li><li>Set up log monitoring</li><li>Enable database backups</li></ul>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Nginx Configuration',
              'summary' => 'Nginx as a reverse proxy',
              'article' => '<h2>Nginx Configuration</h2><pre><code>server {\n    listen 80;\n    server_name example.com;\n\n    location /api {\n        proxy_pass http://ml-php;\n    }\n\n    location / {\n        proxy_pass http://ml-nextjs:3000;\n    }\n}</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        // ─── Best Practices ────────────────────────────
        [
          'entry_slug' => 'coding-conventions',
          'descriptions' => [
            [
              'title' => 'PHP Coding Standards',
              'summary' => 'PSR-12 and project conventions',
              'article' => '<h2>PHP Standards</h2><p>We follow PSR-12 coding standards enforced by PHP-CS-Fixer:</p><ul><li>Use <code>camelCase</code> for methods, <code>PascalCase</code> for classes</li><li>Type-hint all function parameters and return types</li><li>Document public APIs with PHPDoc</li><li>Max line length: 120 characters</li></ul>',
              'rank_order' => 1,
            ],
            [
              'title' => 'TypeScript Conventions',
              'summary' => 'TypeScript and React best practices',
              'article' => '<h2>TypeScript Standards</h2><ul><li>Strict mode enabled (<code>strict: true</code> in tsconfig)</li><li>Use <code>interface</code> for object shapes, <code>type</code> for unions</li><li>Avoid <code>any</code> — use <code>unknown</code> and narrow with type guards</li><li>Name React components with PascalCase</li><li>Export components as default, utilities as named</li></ul>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'error-handling',
          'descriptions' => [
            [
              'title' => 'API Error Responses',
              'summary' => 'Standardized error response format',
              'article' => '<h2>Error Response Format</h2><pre><code>{\n  "message": "The given data was invalid.",\n  "errors": {\n    "email": ["The email field is required."]\n  }\n}</code></pre><p>HTTP status codes follow RESTful conventions: <code>400</code> for validation, <code>401</code> for unauthenticated, <code>403</code> for unauthorized, <code>404</code> for not found, <code>500</code> for server errors.</p>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Exception Handling',
              'summary' => 'Handling exceptions in Laravel',
              'article' => '<h2>Laravel Exception Handling</h2><p>Customize error rendering in <code>bootstrap/app.php</code>:</p><pre><code>->withExceptions(function (Exceptions $exceptions) {\n    $exceptions->render(function (NotFoundException $e) {\n        return response()->json([\n            "message" => "Resource not found"\n        ], 404);\n    });\n})</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'performance-optimization',
          'descriptions' => [
            [
              'title' => 'Database Optimization',
              'summary' => 'Indexes, queries and caching strategies',
              'article' => '<h2>Database Performance</h2><ul><li>Always index foreign keys and frequently queried columns</li><li>Use eager loading (<code>with()</code>) to prevent N+1</li><li>Cache expensive queries with Redis</li></ul><pre><code>Cache::remember("categories", 3600, fn() => Category::all());</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Next.js Performance',
              'summary' => 'Frontend performance tips',
              'article' => '<h2>Next.js Optimization</h2><ul><li>Use Server Components for data fetching when possible</li><li>Leverage <code>next/image</code> for automatic image optimization</li><li>Use <code>next/font</code> for self-hosted fonts with no layout shift</li><li>Add <code>loading="lazy"</code> to below-fold components</li></ul>',
              'rank_order' => 2,
            ],
          ],
        ],

        // ─── Testing ───────────────────────────────────
        [
          'entry_slug' => 'unit-testing',
          'descriptions' => [
            [
              'title' => 'PHPUnit Setup',
              'summary' => 'Setting up and running unit tests in Laravel',
              'article' => '<h2>Unit Testing with PHPUnit</h2><pre><code>docker exec ml-php php artisan test</code></pre><p>Tests live in <code>tests/Unit/</code>. Use <code>TestCase</code> from Laravel:</p><pre><code>class EntryServiceTest extends TestCase {\n    public function test_finds_entry_by_slug(): void {\n        $entry = Entry::factory()->create([\'slug\' => \'test-slug\']);\n        $result = $this->service->findEntry(\'test-slug\');\n        $this->assertEquals($entry->id, $result->id);\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Factories',
              'summary' => 'Using model factories for test data',
              'article' => '<h2>Model Factories</h2><pre><code>class EntryFactory extends Factory {\n    public function definition(): array {\n        return [\n            \'name\' => fake()->words(3, true),\n            \'slug\' => fake()->slug(),\n            \'rank_order\' => fake()->numberBetween(1, 10),\n            \'is_display\' => true,\n        ];\n    }\n}</code></pre>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'integration-testing',
          'descriptions' => [
            [
              'title' => 'Feature Tests',
              'summary' => 'Writing feature/integration tests for API endpoints',
              'article' => '<h2>Feature Tests</h2><p>Feature tests live in <code>tests/Feature/</code> and test API endpoints end-to-end:</p><pre><code>class EntryApiTest extends TestCase {\n    public function test_can_fetch_entry(): void {\n        $entry = Entry::factory()->create();\n        $response = $this->getJson("/api/docs/entries/{$entry->slug}");\n        $response->assertOk()\n                 ->assertJsonPath(\'data.slug\', $entry->slug);\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
          ],
        ],

        [
          'entry_slug' => 'e2e-testing',
          'descriptions' => [
            [
              'title' => 'Playwright Setup',
              'summary' => 'End-to-end testing with Playwright',
              'article' => '<h2>E2E Testing with Playwright</h2><pre><code>npx playwright install</code></pre><p>Tests live in <code>tests/e2e/</code>:</p><pre><code>test("docs page loads", async ({ page }) => {\n  await page.goto("/docs");\n  await expect(page.locator("h1")).toContainText("Documentation");\n});</code></pre>',
              'rank_order' => 1,
            ],
          ],
        ],

        // ─── Security ──────────────────────────────────
        [
          'entry_slug' => 'jwt-authentication',
          'descriptions' => [
            [
              'title' => 'JWT Setup',
              'summary' => 'Configuring JWT auth in Laravel',
              'article' => '<h2>JWT Configuration</h2><p>Install <code>tymon/jwt-auth</code> and publish config:</p><pre><code>composer require tymon/jwt-auth\nphp artisan vendor:publish --provider="Tymon\\JWTAuth\\Providers\\LaravelServiceProvider"\nphp artisan jwt:secret</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Token Lifecycle',
              'summary' => 'Access and refresh token lifecycle',
              'article' => '<h2>Token Lifecycle</h2><ul><li>Access token: short-lived (15–60 minutes)</li><li>Refresh token: long-lived (7–30 days)</li><li>On expiry: use refresh token to get a new access token</li><li>On refresh token expiry: user must log in again</li></ul>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'authorization',
          'descriptions' => [
            [
              'title' => 'Roles & Permissions',
              'summary' => 'Role-based access control with Spatie',
              'article' => '<h2>Roles & Permissions</h2><p>Using <code>spatie/laravel-permission</code>:</p><pre><code>// Assign role\n$user->assignRole("admin");\n\n// Check permission\n$user->can("edit entries");\n\n// In Controller\n$this->authorize("edit entries");</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'Gate & Policies',
              'summary' => 'Defining authorization rules with Gates and Policies',
              'article' => '<h2>Laravel Policies</h2><pre><code>class EntryPolicy {\n    public function update(User $user, Entry $entry): bool {\n        return $user->hasPermission("edit entries");\n    }\n}</code></pre><p>Register policies in <code>AuthServiceProvider</code> and use <code>$this->authorize(\'update\', $entry)</code> in controllers.</p>',
              'rank_order' => 2,
            ],
          ],
        ],

        [
          'entry_slug' => 'security-best-practices',
          'descriptions' => [
            [
              'title' => 'Input Validation',
              'summary' => 'Validating and sanitizing all user input',
              'article' => '<h2>Input Validation</h2><p>Always use Form Requests to validate input before processing:</p><pre><code>class CreateEntryRequest extends FormRequest {\n    public function rules(): array {\n        return [\n            "name" => ["required", "string", "max:255"],\n            "slug" => ["required", "string", "unique:entry_mgmts,slug"],\n        ];\n    }\n}</code></pre>',
              'rank_order' => 1,
            ],
            [
              'title' => 'CORS Configuration',
              'summary' => 'Setting up CORS for the REST API',
              'article' => '<h2>CORS</h2><p>Configure CORS in <code>config/cors.php</code>:</p><pre><code>\'allowed_origins\' => [env(\'FRONTEND_URL\', \'http://localhost:3000\')],\n\'allowed_methods\' => [\'*\'],\n\'allowed_headers\' => [\'*\'],\n\'supports_credentials\' => true,</code></pre>',
              'rank_order' => 2,
            ],
            [
              'title' => 'SQL Injection Prevention',
              'summary' => 'Preventing SQL injection with Eloquent',
              'article' => '<h2>SQL Injection Prevention</h2><p>Always use Eloquent or the Query Builder which automatically parameterizes queries:</p><pre><code>// Safe\nEntry::where(\'slug\', $slug)->first();\n\n// Also safe\nDB::select(\'SELECT * FROM entries WHERE slug = ?\', [$slug]);\n\n// Never do this!\nDB::select("SELECT * FROM entries WHERE slug = \'" . $slug . "\'");</code></pre>',
              'rank_order' => 3,
            ],
          ],
        ],
      ];

      foreach ($descriptions as $entryData) {
        $entry = EntryMgmt::where('slug', $entryData['entry_slug'])->first();

        if (!$entry) {
          $this->command->warn("Entry {$entryData['entry_slug']} not found");
          continue;
        }

        foreach ($entryData['descriptions'] as $descData) {
          EntryDescriptionMgmt::firstOrCreate(
            [
              'entry_mgmt_id' => $entry->id,
              'title' => $descData['title'],
            ],
            array_merge($descData, [
              'entry_mgmt_id' => $entry->id,
              'parent_id' => 0,
              'status' => 1,
              'is_display' => true,
              'is_delete' => false,
              'created_at' => now(),
              'updated_at' => now(),
            ])
          );
        }
      }

      $this->command->info("✓ Entry Description Management data seeded successfully");
    });
  }
}
