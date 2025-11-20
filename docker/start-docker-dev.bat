@echo off
echo ========================================
echo Starting Docker Development Environment
echo ========================================
echo.

cd /d "\\wsl.localhost\Ubuntu\home\mcwai\projects\my_life_management\docker"

echo Current directory: %CD%
echo.

echo Starting Docker services...
echo.

wsl.exe -d Ubuntu -- bash -c "cd /home/mcwai/projects/my_life_management/docker && docker-compose up -d"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Services started successfully!
    echo ========================================
    echo.
    echo Access points:
    echo   Next.js Frontend: http://localhost:3456
    echo   Nginx (API):      http://localhost:81
    echo   PostgreSQL:       localhost:5502
    echo   Redis:            localhost:6601
    echo.
    echo To view logs:
    echo   wsl.exe -d Ubuntu -- bash -c "cd /home/mcwai/projects/my_life_management/docker && docker-compose logs -f"
    echo.
    echo To stop services:
    echo   wsl.exe -d Ubuntu -- bash -c "cd /home/mcwai/projects/my_life_management/docker && docker-compose down"
    echo.
) else (
    echo.
    echo ========================================
    echo Failed to start services! Error code: %ERRORLEVEL%
    echo ========================================
    echo.
)

pause
