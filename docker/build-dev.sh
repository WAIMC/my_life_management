#!/bin/bash

# Script to build Docker images for development environment
# Navigate to docker directory and build all services

echo "Building Docker images for development environment..."
echo "This may take several minutes..."

cd /home/mcwai/projects/my_life_management/docker

# Build all services
docker-compose build --no-cache

echo "Build complete!"
echo "To start the services, run: docker-compose up -d"
