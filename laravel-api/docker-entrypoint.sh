#!/bin/bash
set -e

echo "============================================"
echo "  Laravel API - Automated Setup Script"
echo "============================================"
echo ""

# Define colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Working directory
LARAVEL_DIR="/var/www/laravel-api"
cd "$LARAVEL_DIR"

# Step 1: Check and create .env file
echo -e "${YELLOW}[1/6] Checking .env file...${NC}"
if [ ! -f ".env" ]; then
    echo -e "${BLUE}  -> .env file not found. Creating from .env.example...${NC}"
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}  ✓ .env file created successfully${NC}"
    else
        echo -e "${RED}  ✗ ERROR: .env.example not found!${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}  ✓ .env file already exists${NC}"
fi
echo ""

# Step 2: Handle vendor directory intelligently
echo -e "${YELLOW}[2/6] Managing Composer packages...${NC}"

# Check if vendor directory exists and is valid
if [ -d "vendor" ] && [ -f "vendor/autoload.php" ]; then
    echo -e "${BLUE}  -> Vendor directory exists, checking if update needed...${NC}"
    
    # Run composer install to update only if needed (fast when no changes)
    composer install \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --optimize-autoloader
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}  ✓ Composer packages verified/updated${NC}"
    else
        echo -e "${YELLOW}  ⚠ Composer update failed, removing vendor and reinstalling...${NC}"
        rm -rf vendor
        composer install \
            --no-interaction \
            --no-progress \
            --prefer-dist \
            --optimize-autoloader
        
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}  ✓ Composer packages installed successfully${NC}"
        else
            echo -e "${RED}  ✗ ERROR: Composer install failed!${NC}"
            exit 1
        fi
    fi
else
    # Vendor doesn't exist or is invalid - fresh install
    echo -e "${BLUE}  -> Vendor directory not found, installing packages...${NC}"
    
    # Try to remove vendor if it exists but is invalid (skip if it's a mount point)
    if [ -d "vendor" ]; then
        echo -e "${BLUE}  -> Removing invalid vendor directory...${NC}"
        rm -rf vendor 2>/dev/null || echo -e "${BLUE}  -> Vendor is a volume mount, will be populated by composer${NC}"
    fi
    
    composer install \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --optimize-autoloader
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}  ✓ Composer packages installed successfully${NC}"
    else
        echo -e "${RED}  ✗ ERROR: Composer install failed!${NC}"
        exit 1
    fi
fi
echo ""

# Step 3: Wait for database to be ready
echo -e "${YELLOW}[3/6] Waiting for database to be ready...${NC}"
MAX_RETRIES=30
RETRY_COUNT=0

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}" > /dev/null 2>&1; then
        echo -e "${GREEN}  ✓ Database is ready${NC}"
        break
    fi
    
    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo -e "${BLUE}  -> Waiting for database... (${RETRY_COUNT}/${MAX_RETRIES})${NC}"
    sleep 2
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
    echo -e "${RED}  ✗ ERROR: Database not ready after ${MAX_RETRIES} attempts${NC}"
    exit 1
fi
echo ""

# Step 4: Generate application key
echo -e "${YELLOW}[4/6] Generating application key...${NC}"
# Check if APP_KEY is already set
if grep -q "APP_KEY=base64:" .env; then
    echo -e "${GREEN}  ✓ Application key already exists${NC}"
else
    echo -e "${BLUE}  -> Running: php artisan key:generate${NC}"
    php artisan key:generate --force
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}  ✓ Application key generated${NC}"
    else
        echo -e "${RED}  ✗ ERROR: Key generation failed${NC}"
        exit 1
    fi
fi
echo ""

# Step 5: Set proper permissions
echo -e "${YELLOW}[5/6] Setting file permissions...${NC}"
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}  ✓ Permissions set${NC}"
echo ""

# Step 6: Run project setup (migrations + permissions)
echo -e "${YELLOW}[6/6] Running project setup (migrations + permissions)...${NC}"
echo -e "${BLUE}  -> Running: php artisan project:setup${NC}"

php artisan project:setup

if [ $? -eq 0 ]; then
    echo -e "${GREEN}  ✓ Project setup completed successfully${NC}"
else
    echo -e "${YELLOW}  ⚠ Project setup completed with warnings (this might be expected)${NC}"
fi
echo ""

# Final summary
echo "============================================"
echo -e "${GREEN}  Setup Complete! Laravel API is ready.${NC}"
echo "============================================"
echo ""
echo "Summary:"
echo "  ✓ Environment file: .env"
echo "  ✓ Composer packages: installed"
echo "  ✓ Application key: generated"
echo "  ✓ Database: migrations completed"
echo "  ✓ Permissions: synced"
echo ""
echo "============================================"
echo "  Starting PHP-FPM..."
echo "============================================"
echo ""

# Start PHP-FPM
exec php-fpm
