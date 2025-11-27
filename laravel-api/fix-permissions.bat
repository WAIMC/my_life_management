@echo off
echo ============================================
echo   Fixing Laravel Permission Issues
echo ============================================
echo.

REM Get container name
echo [1/3] Finding Laravel container...
for /f "tokens=*" %%i in ('docker ps --filter "ancestor=*laravel*" --format "{{.Names}}"') do set CONTAINER_NAME=%%i

if "%CONTAINER_NAME%"=="" (
    echo Looking for any PHP container...
    for /f "tokens=*" %%i in ('docker ps --filter "ancestor=*php*" --format "{{.Names}}"') do set CONTAINER_NAME=%%i
)

if "%CONTAINER_NAME%"=="" (
    echo ERROR: No Laravel/PHP container found!
    echo Please start your Docker containers first.
    pause
    exit /b 1
)

echo Found container: %CONTAINER_NAME%
echo.

REM Fix permissions inside container
echo [2/3] Fixing permissions in container...
docker exec %CONTAINER_NAME% chown -R www-data:www-data /var/www/laravel-api/storage /var/www/laravel-api/bootstrap/cache
docker exec %CONTAINER_NAME% chmod -R 775 /var/www/laravel-api/storage /var/www/laravel-api/bootstrap/cache

if %ERRORLEVEL% EQU 0 (
    echo SUCCESS: Permissions fixed!
) else (
    echo ERROR: Failed to fix permissions
    pause
    exit /b 1
)
echo.

REM Clear Laravel cache
echo [3/3] Clearing Laravel cache...
docker exec %CONTAINER_NAME% php artisan cache:clear
docker exec %CONTAINER_NAME% php artisan config:clear

echo.
echo ============================================
echo   DONE! Please test your API again.
echo ============================================
pause
