Next.js 15, TypeScript, Tailwind CSS, shadcn/ui, Redux saga toolkit, zod, axios, ESLint, Prettier, sonner
 fullcalendar,  hookform, react-hook-form, date-fns, next-i18next, swr, swiper, asyn await, message, const,
 
 my-personal-blog/
├── public/                          # Static assets
│   ├── images/                      # Image files
│   ├── fonts/                       # Custom fonts
│   ├── svg/                         # svg
│   └── favicon.ico
│
├── src/                             # Main source directory
│   ├── app/                         # Next.js 15 App Router
│   │   ├── (routes)/                # Route groups (không ảnh hưởng URL)
│   │   │   ├── blog/
│   │   │   │   ├── [slug]/
│   │   │   │   │   └── page.tsx   # Dynamic blog post page
│   │   │   │   ├── page.tsx       # Blog listing page
│   │   │   │   └── loading.tsx    # Loading UI
│   │   │   ├── about/
│   │   │   │   └── page.tsx
│   │   │   ├── login/
│   │   │   │   └── page.tsx
│   │   │   ├── error/
│   │   │   │   ├── 404/
│   │   │   │   │   └── page.tsx   # Not found
│   │   │   │   └── 403/
│   │   │   │       └── page.tsx   # authorization
│   │   │   └── contact/
│   │   │       └── page.tsx
│   │   ├── api/                    # API Routes
│   │   │   ├── posts/
│   │   │   │   ├── route.ts       # GET all posts
│   │   │   │   └── [id]/
│   │   │   │       └── route.ts   # GET single post
│   │   │   └── contact/
│   │   │       └── route.ts       # POST contact form
│   │   ├── layout.tsx             # Root layout
│   │   ├── page.tsx               # Homepage
│   │   ├── loading.tsx            # Global loading
│   │   └── error.tsx              # Error boundary
│   │
│   ├── components/                 # React components
│   │   ├── ui/                    # shadcn/ui components
│   │   │   ├── button.tsx
│   │   │   ├── card.tsx
│   │   │   ├── input.tsx
│   │   │   ├── dialog.tsx
│   │   │   ├── form.tsx
│   │   │   └── ...
│   │   ├── layout/                # Layout components
│   │   │   ├── header.tsx
│   │   │   ├── footer.tsx
│   │   │   ├── sidebar.tsx
│   │   │   └── navigation.tsx
│   │   ├── blog/                  # Blog-specific components
│   │   │   ├── post-card.tsx
│   │   │   ├── post-header.tsx
│   │   │   ├── post-content.tsx
│   │   │   ├── comment-section.tsx
│   │   │   └── related-posts.tsx
│   │   └── common/                # Common/shared components
│   │       ├── page-header.tsx
│   │       ├── search-bar.tsx
│   │       └── breadcrumb.tsx
│   │
│   ├── lib/                       # Library code & utilities
│   │   ├── api/                   # API client setup
│   │   │   ├── axios-instance.ts  # Axios config with interceptors
│   │   │   └── endpoints.ts       # API endpoints constants
│   │   ├── validations/           # Zod schemas
│   │   │   ├── post-schema.ts
│   │   │   ├── contact-schema.ts
│   │   │   └── comment-schema.ts
│   │   └── utils.ts               # Utility functions (cn, formatDate, etc.)
│   │
│   ├── store/                     # Redux store
│   │   ├── index.ts              # Store configuration
│   │   ├── root-saga.ts          # Root saga
│   │   ├── slices/               # Redux Toolkit slices
│   │   │   ├── posts-slice.ts
│   │   │   ├── ui-slice.ts
│   │   │   └── auth-slice.ts
│   │   └── sagas/                # Redux Sagas
│   │       ├── posts-saga.ts
│   │       └── auth-saga.ts
│   │
│   ├── hooks/                    # Custom React hooks
│   │   ├── use-posts.ts
│   │   ├── use-toast.ts
│   │   └── use-debounce.ts
│   │
│   ├── types/                    # TypeScript type definitions
│   │   ├── post.ts
│   │   ├── user.ts
│   │   └── api.ts
│   │
│   ├── constants/                # Application constants
│   │   ├── routes.ts            # Route paths
│   │   ├── api-urls.ts          # API URLs
│   │   ├── messages.ts          # UI messages
│   │   └── config.ts            # App configuration
│   │
│   └── styles/                  # Global styles
│       └── globals.css          # Tailwind directives & custom CSS
│
├── .env.local                   # Local environment variables
├── .env.example                 # Example environment variables
├── .eslintrc.json              # ESLint configuration
├── .prettierrc                 # Prettier configuration
├── .gitignore
├── next.config.ts              # Next.js config (TypeScript support)
├── tailwind.config.ts          # Tailwind configuration
├── tsconfig.json               # TypeScript configuration
├── components.json             # shadcn/ui configuration
├── package.json
└── README.md
/////////////////////////////////////////////////


npx create-next-app@latest my-personal-blog --typescript --tailwind --app --use-pnpm
✔ Which linter would you like to use? › ESLint
✔ Would you like to use React Compiler? … Yes
✔ Would you like your code inside a `src/` directory? …  Yes
✔ Would you like to use Turbopack? (recommended) …  Yes
✔ Would you like to customize the import alias (`@/*` by default)? … Yes
✔ What import alias would you like configured? … @/*

cd my-personal-blog

Bước 1.2: Cài đặt Dependencies chính

# UI Components
npx shadcn-ui@latest init

# State Management
pnpm install @reduxjs/toolkit react-redux redux-saga

# Form & Validation
pnpm install zod react-hook-form @hookform/resolvers

# HTTP Client
pnpm install axios

# Notifications
pnpm install react-hot-toast
# hoặc: pnpm install sonner

# Utilities
pnpm install clsx tailwind-merge
pnpm install date-fns # hoặc dayjs cho date formatting

Bước 1.3: Cài đặt Dev Dependencies

pnpm install -D @types/react @types/node
pnpm install -D eslint-config-prettier eslint-plugin-prettier
pnpm install -D prettier prettier-plugin-tailwindcss
pnpm install -D @typescript-eslint/parser @typescript-eslint/eslint-plugin

- Done setup common redux toolkit + saga
- Done setup common call api, handle request sent and response revices