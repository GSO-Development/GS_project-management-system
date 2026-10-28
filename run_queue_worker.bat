@echo off
cd /d "%~dp0"
echo Starting GS NexusPM background document worker...
php artisan queue:work database --queue=default --tries=1 --timeout=180 --sleep=2
