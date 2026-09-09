@echo off
title POS Audit Script
echo ============================================
echo        POS SERVER AUDIT REPORT
echo ============================================
echo.

echo [1] PHP Version:
"C:\Program Files\php-8.5.9\php.exe" -v
echo.

echo [2] PHP Processes Running:
tasklist /FI "IMAGENAME eq php.exe" /V
echo.

echo [3] Port 8080 Status:
netstat -ano | findstr ":8080"
echo.

echo [4] All Listening Ports (8xxx):
netstat -ano | findstr "LISTENING" | findstr ":8"
echo.

echo [5] Server Can Respond (localhost test):
"C:\Program Files\php-8.5.9\php.exe" -r "$x=@file_get_contents('http://127.0.0.1:8080/login'); echo $x ? 'OK - Login page loads ('.strlen($x).' bytes)' : 'FAIL - No response'; echo PHP_EOL;"
echo.

echo [6] Firewall Rules for Port 8080:
netsh advfirewall firewall show rule name=all dir=in | findstr /C:"Rule Name" /C:"Enabled" /C:"LocalPort" /C:"Action" /C:"---"
echo.

echo [7] Network Info:
ipconfig | findstr "IPv4"
echo.

echo [8] Network Profile:
powershell -Command "Get-NetConnectionProfile | Select-Object Name, InterfaceAlias, NetworkCategory | Format-Table"
echo.

echo [9] .env APP_URL:
findstr "APP_URL" "C:\Users\ALI.A.SALAH\Desktop\pos\.env"
echo.

echo [10] Laravel Config cached?
if exist "C:\Users\ALI.A.SALAH\Desktop\pos\bootstrap\cache\config.php" (
    echo WARNING: Config is CACHED! Run: php artisan config:clear
) else (
    echo OK: No cached config
)
echo.

echo [11] Session Driver:
findstr "SESSION_DRIVER" "C:\Users\ALI.A.SALAH\Desktop\pos\.env"
echo.

echo [12] Routes:
"C:\Program Files\php-8.5.9\php.exe" artisan route:list --columns=method,uri,name 2>&1
echo.

echo ============================================
echo              AUDIT COMPLETE
echo ============================================
echo.
echo If server is NOT running, start it with:
echo   "C:\Program Files\php-8.5.9\php.exe" artisan serve --host=0.0.0.0 --port=8080
echo.
echo Access from other devices:
echo   http://192.168.0.108:8080
echo.
pause
