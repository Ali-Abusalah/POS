Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = "C:\Users\ALI.A.SALAH\Desktop\pos"
WshShell.Run """C:\Program Files\php-8.5.9\php.exe"" artisan serve --host=0.0.0.0 --port=8000", 0, False
