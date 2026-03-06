#!/bin/sh

# Wait for dependencies if needed
echo "Starting Next.js Docs development server..."

# Install dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
  echo "Installing dependencies..."
  npm install
fi

# Start Next.js in development mode
exec npm run dev
