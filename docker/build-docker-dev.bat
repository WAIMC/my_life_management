@echo off
echo ========================================
echo Building Docker Development Environment
echo ========================================
echo.

cd /d "\\wsl.localhost\Ubuntu\home\mcwai\projects\my_life_management\docker"

echo Current directory: %CD%
echo.

echo Building Docker images...
echo This may take several minutes...
echo.

wsl.exe -d Ubuntu -- bash -c "cd /home/mcwai/projects/my_life_management/docker && docker-compose build"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Build completed successfully!
    echo ========================================
    echo.
    echo To start the services, run:
    echo   wsl.exe -d Ubuntu -- bash -c "cd /home/mcwai/projects/my_life_management/docker && docker-compose up -d"
    echo.
) else (
    echo.
    echo ========================================
    echo Build failed! Error code: %ERRORLEVEL%
    echo ========================================
    echo.
    echo Please check the error messages above.
    echo You can also try running the command manually in WSL:
    echo   cd /home/mcwai/projects/my_life_management/docker
    echo   docker-compose build
    echo.
)

pause
