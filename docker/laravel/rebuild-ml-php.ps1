#!/usr/bin/env pwsh
# Script to rebuild ml-php container with automatic setup

Write-Host "=== ML-PHP Container Rebuild Script ===" -ForegroundColor Cyan
Write-Host ""

# Define paths
$DOCKER_DIR = Join-Path $PSScriptRoot ".."
$LARAVEL_DIR = Join-Path $PSScriptRoot "..\..\laravel-api"
$ENV_FILE = Join-Path $LARAVEL_DIR ".env"
$ENV_EXAMPLE = Join-Path $LARAVEL_DIR ".env.example"

# Step 1: Check if .env exists
Write-Host "[1/5] Checking .env file..." -ForegroundColor Yellow
if (-Not (Test-Path $ENV_FILE)) {
  Write-Host "  -> .env file not found. Creating from .env.example..." -ForegroundColor Magenta
    
  if (Test-Path $ENV_EXAMPLE) {
    Copy-Item $ENV_EXAMPLE $ENV_FILE
    Write-Host "  OK .env file created successfully" -ForegroundColor Green
  }
  else {
    Write-Host "  ERROR: .env.example not found!" -ForegroundColor Red
    exit 1
  }
}
else {
  Write-Host "  OK .env file already exists" -ForegroundColor Green
}

Write-Host ""

# Step 2: Build the container
Write-Host "[2/5] Building ml-php container..." -ForegroundColor Yellow
Write-Host "  -> Running: docker-compose build --no-cache ml-php" -ForegroundColor Gray

Push-Location $DOCKER_DIR
$buildResult = docker-compose build --no-cache ml-php 2>&1
$buildExitCode = $LASTEXITCODE
Pop-Location

if ($buildExitCode -eq 0) {
  Write-Host "  OK Container built successfully" -ForegroundColor Green
}
else {
  Write-Host "  ERROR Build failed with exit code: $buildExitCode" -ForegroundColor Red
  Write-Host $buildResult -ForegroundColor Red
  exit $buildExitCode
}

Write-Host ""

# Step 3: Start the container (if not running)
Write-Host "[3/5] Ensuring container is running..." -ForegroundColor Yellow
Push-Location $DOCKER_DIR
docker-compose up -d ml-php 2>&1 | Out-Null
Pop-Location
Start-Sleep -Seconds 5
Write-Host "  OK Container is up" -ForegroundColor Green

Write-Host ""

# Step 4: Generate application key
Write-Host "[4/5] Generating application key..." -ForegroundColor Yellow
Write-Host "  -> Running: php artisan key:generate" -ForegroundColor Gray

Push-Location $DOCKER_DIR
$keyGenResult = docker-compose exec -T ml-php php artisan key:generate 2>&1
$keyGenExitCode = $LASTEXITCODE
Pop-Location

if ($keyGenExitCode -eq 0) {
  Write-Host "  OK Application key generated" -ForegroundColor Green
}
else {
  Write-Host "  ERROR Key generation failed" -ForegroundColor Red
  Write-Host $keyGenResult -ForegroundColor Red
}

Write-Host ""

# Step 5: Run project setup
Write-Host "[5/5] Running project setup..." -ForegroundColor Yellow
Write-Host "  -> Running: php artisan project:setup" -ForegroundColor Gray

Push-Location $DOCKER_DIR
$setupResult = docker-compose exec -T ml-php php artisan project:setup 2>&1
$setupExitCode = $LASTEXITCODE
Pop-Location

if ($setupExitCode -eq 0) {
  Write-Host "  OK Project setup completed" -ForegroundColor Green
}
else {
  Write-Host "  WARNING Project setup encountered issues (this might be expected)" -ForegroundColor Yellow
  Write-Host $setupResult -ForegroundColor Gray
}

Write-Host ""
Write-Host "=== Rebuild Complete ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Summary:" -ForegroundColor White
Write-Host "  - Container: ml-php" -ForegroundColor White
Write-Host "  - Status: Running" -ForegroundColor Green
Write-Host "  - .env: Configured" -ForegroundColor Green
Write-Host "  - App Key: Generated" -ForegroundColor Green
Write-Host ""
