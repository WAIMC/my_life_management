#!/bin/bash
echo "Stopping containers..."
docker compose -f ../docker/docker-compose.yml down

echo "Cleaning Next.js cache..."
rm -rf .next
rm -rf node_modules/.cache

echo "Rebuilding and starting frontend..."
docker compose -f ../docker/docker-compose.yml up -d --build ml-nextjs

echo "Done! Access the app at http://localhost:81/admin"
