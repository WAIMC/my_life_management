# Multi-stage build for Next.js Documentation
FROM node:20-alpine AS base

# Install dependencies only when needed
FROM base AS deps
RUN apk add --no-cache libc6-compat
WORKDIR /app

# Enable pnpm
RUN corepack enable && corepack prepare pnpm@latest --activate

# Copy package files
COPY package.json pnpm-lock.yaml* package-lock.json* ./

# Install dependencies
RUN \
  if [ -f pnpm-lock.yaml ]; then \
    pnpm install --frozen-lockfile; \
  elif [ -f package-lock.json ]; then \
    npm ci; \
  else \
    npm install; \
  fi

# Rebuild the source code only when needed
FROM base AS builder
WORKDIR /app

# Enable pnpm
RUN corepack enable && corepack prepare pnpm@latest --activate

COPY --from=deps /app/node_modules ./node_modules
COPY . .

# Set environment variables for build
ENV NEXT_TELEMETRY_DISABLED=1
ENV NODE_ENV=production

# Build Next.js app
RUN \
  if [ -f pnpm-lock.yaml ]; then \
    pnpm run build; \
  else \
    npm run build; \
  fi

# Production image, copy all the files and run next
FROM base AS runner
WORKDIR /app

ENV NODE_ENV=production
ENV NEXT_TELEMETRY_DISABLED=1

RUN addgroup --system --gid 1001 nodejs
RUN adduser --system --uid 1001 nextjs

# Copy built application
COPY --from=builder /app/public ./public

# Set correct permissions for prerender cache
RUN mkdir .next
RUN chown nextjs:nodejs .next

# Copy built Next.js app
COPY --from=builder --chown=nextjs:nodejs /app/.next/standalone ./
COPY --from=builder --chown=nextjs:nodejs /app/.next/static ./.next/static

USER nextjs

EXPOSE 3457

ENV PORT=3457
ENV HOSTNAME="0.0.0.0"

CMD ["node", "server.js"]

# ============ Stage: Development (Optimized for Hot Reload) ============
FROM base AS development

WORKDIR /app

# Install system dependencies for development
RUN apk add --no-cache wget bash libc6-compat

# Enable pnpm
RUN corepack enable && corepack prepare pnpm@latest --activate

# Set environment variables
ENV NODE_ENV=development
ENV NEXT_TELEMETRY_DISABLED=1
ENV HOSTNAME=0.0.0.0
ENV PORT=3457

# Copy package files first for better layer caching
COPY package.json pnpm-lock.yaml* package-lock.json* ./

# Install dependencies in the image (not at runtime)
RUN if [ -f pnpm-lock.yaml ]; then \
      echo "Using pnpm..." && \
      pnpm install --frozen-lockfile && \
      pnpm add -D @next/swc-linux-x64-musl; \
    elif [ -f package-lock.json ]; then \
      echo "Using npm with legacy peer deps..." && \
      npm ci --legacy-peer-deps && \
      npm install --save-dev @next/swc-linux-x64-musl; \
    else \
      echo "Using npm..." && \
      npm install --legacy-peer-deps && \
      npm install --save-dev @next/swc-linux-x64-musl; \
    fi

# Note: Source code will be mounted as volume at runtime
# This allows hot-reload without rebuilding the image

# Expose port
EXPOSE 3457

# Start Next.js development server
CMD ["npm", "run", "dev"]
