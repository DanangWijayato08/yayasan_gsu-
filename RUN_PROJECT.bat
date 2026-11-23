@echo off
REM ============================================
REM GSU Website - Project Runner Script
REM ============================================

echo.
echo ========================================
echo  GSU WEBSITE - STARTING SERVICES
echo ========================================
echo.

REM Start Backend
echo [1] Starting Backend (Laravel)...
start "GSU Backend" cmd /k "cd /d d:\GSU\gsu-website\backend && php artisan serve --host=127.0.0.1 --port=8000"

REM Wait for backend to start
timeout /t 3 /nobreak

REM Start Frontend
echo [2] Starting Frontend (Vue 3)...
start "GSU Frontend" cmd /k "cd /d d:\GSU\gsu-website\frontend && npm run dev"

echo.
echo ========================================
echo  SERVICES STARTING...
echo ========================================
echo.
echo Frontend  : http://localhost:5175
echo Admin     : http://localhost:5175/admin/login
echo Backend   : http://127.0.0.1:8000
echo Database  : http://localhost:8080
echo.
echo Email    : admin@sedekahumat.org
echo Password : admin123456
echo.
echo Waiting for services to start (30 seconds)...
timeout /t 30 /nobreak

echo.
echo Opening Admin Panel in browser...
start http://localhost:5175/admin/login

echo.
echo ========================================
echo  ALL SERVICES STARTED SUCCESSFULLY!
echo ========================================
echo.
echo Press any key to continue...
pause
