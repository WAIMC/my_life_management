#!/usr/bin/env pwsh
# Script to test automated Laravel setup
# This simulates a fresh clone scenario

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Testing Automated Laravel Setup"
Write-Host "============================================"
Write-Host ""

$DOCKER_DIR = Join-Path $PSScriptRoot ".."
$LARAVEL_DIR = Join-Path $PSScriptRoot "..\..\laravel-api"

# Confirm with user
Write-Host "This script will test the automated setup by:" -ForegroundColor Yellow
Write-Host "  1. Stopping all containers" -ForegroundColor White
Write-Host "  2. Removing .env file (will be recreated automatically)" -ForegroundColor White
Write-Host "  3. Removing vendor folder (will be reinstalled automatically)" -ForegroundColor White
Write-Host "  4. Building and starting containers" -ForegroundColor White
Write-Host "  5. Showing logs to verify setup" -ForegroundColor White
Write-Host ""

$confirmation = Read-Host "Do you want to continue? (y/N)"
if ($confirmation -ne 'y' -and $confirmation -ne 'Y') {
  Write-Host "Test cancelled." -ForegroundColor Yellow
  exit 0
}

Write-Host ""
Write-Host "[Step 1/5] Stopping containers..." -ForegroundColor Yellow
Push-Location $DOCKER_DIR
docker-compose down
Pop-Location
Write-Host "  OK Containers stopped" -ForegroundColor Green
Write-Host ""

Write-Host "[Step 2/5] Removing .env file..." -ForegroundColor Yellow
$envFile = Join-Path $LARAVEL_DIR ".env"
if (Test-Path $envFile) {
  Remove-Item $envFile -Force
  Write-Host "  OK .env file removed" -ForegroundColor Green
}
else {
  Write-Host "  OK .env file doesn't exist" -ForegroundColor Green
}
Write-Host ""

Write-Host "[Step 3/5] Removing vendor folder..." -ForegroundColor Yellow
$vendorDir = Join-Path $LARAVEL_DIR "vendor"
if (Test-Path $vendorDir) {
  Write-Host "  -> Removing vendor folder (this may take a moment)..." -ForegroundColor Gray
  Remove-Item -Recurse -Force $vendorDir
  Write-Host "  OK Vendor folder removed" -ForegroundColor Green
}
else {
  Write-Host "  OK Vendor folder doesn't exist" -ForegroundColor Green
}
Write-Host ""

Write-Host "[Step 4/5] Building and starting containers..." -ForegroundColor Yellow
Write-Host "  -> This will take 2-3 minutes on first build..." -ForegroundColor Gray
Push-Location $DOCKER_DIR
docker-compose up --build -d
Pop-Location
Write-Host "  OK Containers started" -ForegroundColor Green
Write-Host ""

Write-Host "[Step 5/5] Showing setup logs..." -ForegroundColor Yellow
Write-Host "  -> Watch for the automated setup process..." -ForegroundColor Gray
Write-Host "  -> Press Ctrl+C to stop watching logs" -ForegroundColor Gray
Write-Host ""
Start-Sleep -Seconds 3

Push-Location $DOCKER_DIR
docker-compose logs -f ml-php
Pop-Location

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Test Complete!"
Write-Host "============================================"
Write-Host ""
Write-Host "Verification:" -ForegroundColor White
Write-Host "  - Check if .env file was created: docker-compose exec ml-php ls -la .env" -ForegroundColor Gray
Write-Host "  - Check if vendor exists: docker-compose exec ml-php ls -la vendor | head" -ForegroundColor Gray
Write-Host "  - Check container status: docker-compose ps" -ForegroundColor Gray
Write-Host ""
