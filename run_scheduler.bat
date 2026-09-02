@echo off
REM GS Project Management - Laravel Scheduler
:loop
    echo [%DATE% %TIME%] Running Laravel Scheduler...
    "C:\xampp\php\php.exe" artisan schedule:run
    echo Done. Waiting 15 minutes...
    timeout /t 900 /nobreak
goto loop
