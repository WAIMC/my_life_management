#!/bin/bash

# Configuration
PROJECT_NAME="second_memory"
BASE_DIR="/home/vinhdv/actions-runner/_work/second_memory/second_memory"
CI_CD_DIR="$BASE_DIR/ci-cd"
DOCKER_DIR="$BASE_DIR/docker"
NGINX_CONF_PATH="/etc/nginx/sites-available/$PROJECT_NAME"
NGINX_ENABLED_PATH="/etc/nginx/sites-enabled/$PROJECT_NAME"

# Determine which environment is currently active
if grep -q "backend_blue" $NGINX_CONF_PATH 2>/dev/null; then
    CURRENT_ENV="blue"
    TARGET_ENV="green"
    TARGET_PORT=8081
else
    CURRENT_ENV="green"
    TARGET_ENV="blue"
    TARGET_PORT=8080
fi

echo "Current environment: $CURRENT_ENV"
echo "Deploying to $TARGET_ENV environment on port $TARGET_PORT..."

# 1. Pull latest images
# docker compose -f $DOCKER_DIR/docker-compose.yml pull

# 2. Start new environment
echo "Starting $TARGET_ENV containers..."
export TARGET_PORT=$TARGET_PORT
docker compose -f $DOCKER_DIR/docker-compose.yml -p "${PROJECT_NAME}_${TARGET_ENV}" up -d --build

# 3. Health Check
echo "Performing health check on $TARGET_ENV..."
MAX_RETRIES=12
COUNT=0
HEALTHY=false

while [ $COUNT -lt $MAX_RETRIES ]; do
    if curl -s -I "http://localhost:$TARGET_PORT" | grep -q "200 OK"; then
        HEALTHY=true
        break
    fi
    echo "Waiting for $TARGET_ENV to be healthy... ($((COUNT+1))/$MAX_RETRIES)"
    sleep 10
    COUNT=$((COUNT+1))
done

if [ "$HEALTHY" = false ]; then
    echo "Health check failed for $TARGET_ENV. Rolling back..."
    docker compose -f $DOCKER_DIR/docker-compose.yml -p "${PROJECT_NAME}_${TARGET_ENV}" down
    exit 1
fi

# 4. Switch Traffic
echo "Switching traffic to $TARGET_ENV..."
sed "s/backend_ENV_TARGET/backend_$TARGET_ENV/g" $CI_CD_DIR/nginx-proxy.conf.template | sudo tee $NGINX_CONF_PATH
sudo ln -sf $NGINX_CONF_PATH $NGINX_ENABLED_PATH
sudo systemctl reload nginx

# 5. Cleanup Old Environment
echo "Cleaning up $CURRENT_ENV environment..."
docker compose -f $DOCKER_DIR/docker-compose.yml -p "${PROJECT_NAME}_${CURRENT_ENV}" down

echo "Deployment to $TARGET_ENV successful!"
