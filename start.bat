@echo off
echo Starting site in Docker...
docker compose up -d --build
echo.
echo ================================
echo   Site:  http://localhost:8080
echo   Admin: http://localhost:8080/admin/login.php
echo ================================
echo.
start http://localhost:8080
pause
