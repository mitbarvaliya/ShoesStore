@echo off
REM Laravel Market Data Scheduler
REM Run this file to start the scheduler automatically

cd /d "%~dp0"

echo Starting Laravel Scheduler...
echo This will fetch market data every minute automatically.
echo Press Ctrl+C to stop.
echo.

php artisan schedule:work
