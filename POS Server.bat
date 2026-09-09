@echo off
title POS Server - Running
echo ============================================
echo         POS SERVER
echo ============================================
echo.
echo Starting server...
echo.
echo Access URLs:
echo   This PC:      http://localhost:8080
echo   Same Network: http://192.168.0.119:8080
echo.
echo Login: admin@pos.local / password
echo.
echo ============================================
echo   DO NOT CLOSE THIS WINDOW
echo   Press Ctrl+C to stop the server
echo ============================================
echo.
cd /d "C:\Users\ALI.A.SALAH\Desktop\pos"
"C:\Program Files\php-8.5.9\php.exe" artisan serve --host=0.0.0.0 --port=8080
pause
